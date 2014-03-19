<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 07.02.14 - 18:11
 */

namespace GingerCore\Model\JsonWDL;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;
use GingerCore\Model\JsonWDL\Action\ActionDescription;
use GingerCore\Model\JsonWDL\Action\Arguments;
use GingerCore\Model\JsonWDL\Action\EventsDefinition;
use GingerCore\Model\JsonWDL\Action\Name;
use GingerCore\Model\JsonWDL\Action\StructureDefinition;
use GingerCore\Model\JsonWDL\Action\Type;
use GingerCore\Model\JsonWDL\Agent\AgentDescription;
use GingerCore\Model\JsonWDL\Agent\AgentOptions;
use GingerCore\Model\JsonWDL\Role\RoleDescription;
use GingerCore\Model\JsonWDL\Role\RoleName;
use GingerCore\Model\JsonWDL\Role\RoleOptions;
use GingerCore\Model\Workflow\WorkflowId;
use GingerCore\Util\Util;

/**
 * Class WorkflowDescription
 *
 * @package GingerCore\Model\JsonWDL
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowDescription implements ValueObjectInterface
{
    /**
     * @var WorkflowId
     */
    private $workflowId;

    /**
     * @var ProcessStep[]
     */
    private $processSteps = array();

    /**
     * @var string
     */
    private $jsonWorkflowDescription;

    /**
     * @param string $aJsonWorkflowDescription
     * @return WorkflowDescription
     */
    public static function fromJson($aJsonWorkflowDescription)
    {
        $arrayWorkflowDescription = json_decode($aJsonWorkflowDescription, true);

        Assertion::isArray(
            $arrayWorkflowDescription,
            sprintf(
                "Invalid JsonWorkflowDescription provided. Could not be converted to array: %s",
                $aJsonWorkflowDescription
            )
        );

        Assertion::keyExists(
            $arrayWorkflowDescription,
            'workflow',
            sprintf(
                "Missing workflow property in JsonWorkflowDescription: %s",
                $aJsonWorkflowDescription
            )
        );

        Assertion::keyExists(
            $arrayWorkflowDescription,
            'process',
            sprintf(
                "Missing process property in JsonWorkflowDescription",
                $aJsonWorkflowDescription
            )
        );
        Assertion::isArray(
            $arrayWorkflowDescription['process'],
            sprintf(
                "Process definition must be an array: %s given in %s",
                Util::getType($arrayWorkflowDescription),
                $aJsonWorkflowDescription
            )
        );

        $processStepCollection = array();

        foreach($arrayWorkflowDescription['process'] as $processStepDefinition) {
            $processStepCollection[] = static::processStepFromDefinition($processStepDefinition);
        }

        return new static(
            new WorkflowId($arrayWorkflowDescription['workflow']),
            $processStepCollection,
            $aJsonWorkflowDescription
        );
    }

    /**
     * @param array $aProcessStepDefinition
     * @return ProcessStep
     * @throws \InvalidArgumentException
     */
    public static function processStepFromDefinition(array $aProcessStepDefinition)
    {
        if (isset($aProcessStepDefinition['agent'])) {
            Assertion::isArray(
                $aProcessStepDefinition['agent'],
                sprintf(
                    "The agent property of an ProcessStepDefinition must be an array: %s given in %s",
                    Util::getType($aProcessStepDefinition['agent']),
                    json_encode($aProcessStepDefinition)
                )
            );

            return ProcessStep::fromAgentDescription(
                static::agentDescriptionFromDefinition($aProcessStepDefinition['agent'])
            );

        } else if (isset($aProcessStepDefinition['role']) && isset($aProcessStepDefinition['action'])) {

            Assertion::isArray(
                $aProcessStepDefinition['role'],
                sprintf(
                    "The role property of an ProcessStepDefinition must be an array: %s given in %s",
                    Util::getType($aProcessStepDefinition['role']),
                    json_encode($aProcessStepDefinition)
                )
            );

            Assertion::isArray(
                $aProcessStepDefinition['action'],
                sprintf(
                    "The action property of an ProcessStepDefinition must be an array: %s given in %s",
                    Util::getType($aProcessStepDefinition['action']),
                    json_encode($aProcessStepDefinition)
                )
            );

            return ProcessStep::fromRoleAndActionDescription(
                static::roleDescriptionFromDefinition($aProcessStepDefinition['role']),
                static::actionDescriptionFromDefinition($aProcessStepDefinition['action'])
            );
        } else {
            throw new \InvalidArgumentException('Invalid ProcessStep. Missing agent or role and action properties');
        }
    }

    /**
     * @param array $anAgentDefinition
     * @return AgentDescription
     */
    public static function agentDescriptionFromDefinition(array $anAgentDefinition)
    {
        Assertion::keyExists(
            $anAgentDefinition,
            'type',
            sprintf(
                "Missing type property in AgentDefinition: %s",
                json_encode($anAgentDefinition)
            )
        );

        Assertion::keyExists(
            $anAgentDefinition,
            'process',
            sprintf(
                "Missing process property in AgentDefinition",
                json_encode($anAgentDefinition)
            )
        );
        Assertion::isArray(
            $anAgentDefinition['process'],
            sprintf(
                "The process property of an AgentDefinition must be an array: %s given in %s",
                Util::getType($anAgentDefinition['process']),
                json_encode($anAgentDefinition)
            )
        );

        $processStepCollection = array();

        foreach($anAgentDefinition['process'] as $processStepDefinition) {
            $processStepCollection[] = static::processStepFromDefinition($processStepDefinition);
        }

        if (isset($anAgentDefinition['options'])) {
            Assertion::isArray(
                $anAgentDefinition['options'],
                sprintf(
                    "The options property of AgentDefinition must be an array: %s given in %s",
                    Util::getType($anAgentDefinition['options']),
                    json_encode($anAgentDefinition)
                )
            );

            $agentOptions = new AgentOptions($anAgentDefinition['options']);
        } else {
            $agentOptions = new AgentOptions(array());
        }

        return new AgentDescription($anAgentDefinition['type'], $processStepCollection, $agentOptions);
    }

    /**
     * @param array $aRoleDescriptionDefinition
     * @return RoleDescription
     */
    public static function roleDescriptionFromDefinition(array $aRoleDescriptionDefinition)
    {
        Assertion::keyExists(
            $aRoleDescriptionDefinition,
            'name',
            sprintf(
                "Missing name property in RoleDescriptionDefinition: %s",
                json_encode($aRoleDescriptionDefinition)
            )
        );

        if (isset($aRoleDescriptionDefinition['options'])) {
            Assertion::isArray(
                $aRoleDescriptionDefinition['options'],
                sprintf(
                    "The options property of RoleDescriptionDefinition must be an array: %s given in %s",
                    Util::getType($aRoleDescriptionDefinition['options']),
                    json_encode($aRoleDescriptionDefinition)
                )
            );

            $roleOptions = new RoleOptions($aRoleDescriptionDefinition['options']);
        } else {
            $roleOptions = new RoleOptions(array());
        }

        return new RoleDescription(new RoleName($aRoleDescriptionDefinition['name']), $roleOptions);
    }

    /**
     * @param array $anActionDescriptionDefinition
     * @return ActionDescription
     */
    public static function actionDescriptionFromDefinition(array $anActionDescriptionDefinition)
    {
        Assertion::keyExists(
            $anActionDescriptionDefinition,
            'name',
            sprintf(
                "Missing name property in ActionDescriptionDefinition: %s",
                json_encode($anActionDescriptionDefinition)
            )
        );

        Assertion::keyExists(
            $anActionDescriptionDefinition,
            'type',
            sprintf(
                "Missing type property in ActionDescriptionDefinition: %s",
                json_encode($anActionDescriptionDefinition)
            )
        );

        if ($anActionDescriptionDefinition['type'] === Type::COMMAND) {
            Assertion::keyExists(
                $anActionDescriptionDefinition,
                'events',
                sprintf(
                    "Missing events property in ActionDescriptionDefinition: %s",
                    json_encode($anActionDescriptionDefinition)
                )
            );

            $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions($anActionDescriptionDefinition['events']);
        } else {
            $eventsDefinition = null;
        }

        if (isset($anActionDescriptionDefinition['arguments'])) {
            Assertion::isArray(
                $anActionDescriptionDefinition['arguments'],
                sprintf(
                    "The arguments property of ActionDescriptionDefinition must be an array: %s given in %s",
                    Util::getType($anActionDescriptionDefinition['arguments']),
                    json_encode($anActionDescriptionDefinition)
                )
            );

            $arguments = new Arguments($anActionDescriptionDefinition['arguments']);
        } else {
            $arguments = new Arguments(array());
        }

        if (isset($anActionDescriptionDefinition['structure'])) {
            Assertion::isArray(
                $anActionDescriptionDefinition['structure'],
                sprintf(
                    "The structure property of ActionDescriptionDefinition must be an array: %s given in %s",
                    Util::getType($anActionDescriptionDefinition['structure']),
                    json_encode($anActionDescriptionDefinition)
                )
            );

            $structureDefinition = StructureDefinition::fromPathValueTypeList($anActionDescriptionDefinition['structure']);
        } else {
            $structureDefinition = null;
        }

        if ($anActionDescriptionDefinition['type'] === Type::COMMAND) {
            return ActionDescription::asCommand(
                new Name($anActionDescriptionDefinition['name']),
                $eventsDefinition,
                $arguments,
                $structureDefinition
            );
        } else {
            return ActionDescription::asQuery(
                new Name($anActionDescriptionDefinition['name']),
                $arguments,
                $structureDefinition
            );
        }
    }

    /**
     * @param WorkflowId    $aWorkflowId
     * @param ProcessStep[] $aProcessStepCollection
     * @param string        $aJsonWorkflowDescription
     */
    private function __construct(WorkflowId $aWorkflowId, array $aProcessStepCollection, $aJsonWorkflowDescription)
    {
        $this->workflowId              = $aWorkflowId;
        $this->processSteps            = $aProcessStepCollection;
        $this->jsonWorkflowDescription = $aJsonWorkflowDescription;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return $this->workflowId;
    }

    /**
     * @return ProcessStep[]
     */
    public function processSteps()
    {
        return $this->processSteps;
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (! $other instanceof WorkflowDescription) {
            return false;
        }

        if (! $this->workflowId()->sameValueAs($other->workflowId())) {
            return false;
        }

        if (count($this->processSteps()) !== count($other->processSteps())) {
            return false;
        }

        $otherProcessSteps = $other->processSteps();

        foreach($this->processSteps() as $index => $processStep) {
            if (! $processStep->sameValueAs($otherProcessSteps[$index])) {
                return false;
            }
        }

        return true;
    }
}