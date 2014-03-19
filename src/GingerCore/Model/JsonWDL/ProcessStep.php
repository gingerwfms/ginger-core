<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 07.02.14 - 19:06
 */

namespace GingerCore\Model\JsonWDL;

use Assert\Assertion;
use Codeliner\Comparison\EqualsBuilder;
use Codeliner\Domain\Shared\ValueObjectInterface;
use GingerCore\Model\JsonWDL\Action\ActionDescription;
use GingerCore\Model\JsonWDL\Agent\AgentDescription;
use GingerCore\Model\JsonWDL\Role\RoleDescription;

/**
 * Class ProcessStep
 *
 * @package GingerCore\Model\JsonWDL
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ProcessStep implements ValueObjectInterface
{
    /**
     * @var AgentDescription
     */
    private $agentDescription;

    /**
     * @var RoleDescription
     */
    private $role;

    /**
     * @var ActionDescription
     */
    private $actionDescription;

    /**
     * @param AgentDescription $anAgentDescription
     * @return ProcessStep
     */
    public static function fromAgentDescription(AgentDescription $anAgentDescription)
    {
        return new self($anAgentDescription);
    }

    /**
     * @param RoleDescription $aRole
     * @param ActionDescription $anActionDescription
     * @return ProcessStep
     */
    public static function fromRoleAndActionDescription(RoleDescription $aRole, ActionDescription $anActionDescription)
    {
        return new self($aRole, $anActionDescription);
    }

    /**
     * @param $anAgentDescriptionOrRole
     * @param ActionDescription $anActionDescriptionOrNull
     */
    private function __construct($anAgentDescriptionOrRole, ActionDescription $anActionDescriptionOrNull = null)
    {
        if ($anAgentDescriptionOrRole instanceof AgentDescription) {
            $this->agentDescription = $anAgentDescriptionOrRole;
        } else if ($anAgentDescriptionOrRole instanceof RoleDescription) {
            Assertion::notNull($anActionDescriptionOrNull, 'ActionDescription is required when ProcessSep is setup with a RoleDescription.');

            $this->role = $anAgentDescriptionOrRole;
            $this->actionDescription = $anActionDescriptionOrNull;
        }
    }

    /**
     * @return bool
     */
    public function isAgentDescription()
    {
        return !is_null($this->agentDescription);
    }

    /**
     * @return AgentDescription
     */
    public function agentDescription()
    {
        if(!$this->isAgentDescription()) {
            return new AgentDescription(AgentDescription::SEQUENCE, array($this));
        }

        return $this->agentDescription;
    }

    /**
     * @return RoleDescription
     * @throws \RuntimeException
     */
    public function roleDescription()
    {
        if ($this->isAgentDescription()) {
            throw new \RuntimeException('ProcessStep with an AgentDescription does not contain a RoleDescription');
        }

        return $this->role;
    }

    /**
     * @return ActionDescription
     * @throws \RuntimeException
     */
    public function actionDescription()
    {
        if ($this->isAgentDescription()) {
            throw new \RuntimeException('ProcessStep with an AgentDescription does not contain an ActionDescription');
        }

        return $this->actionDescription;
    }


    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof ProcessStep) {
            return false;
        }

        if ($this->isAgentDescription()) {
            if (!$other->isAgentDescription()) {
                return false;
            }

            return $this->agentDescription()->sameValueAs($other->agentDescription());
        } else {
            if ($other->isAgentDescription()) {
                return false;
            }

            return EqualsBuilder::create()
                ->append($this->roleDescription()->sameValueAs($other->roleDescription()), true)
                ->append($this->actionDescription()->sameValueAs($other->actionDescription()), true)
                ->equals();
        }
    }
}