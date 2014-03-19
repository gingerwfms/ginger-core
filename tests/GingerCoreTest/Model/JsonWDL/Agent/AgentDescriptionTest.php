<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 09.02.14 - 21:52
 */

namespace GingerCoreTest\Model\JsonWDL\Agent;

use GingerCore\Model\JsonWDL\Action\ActionDescription;
use GingerCore\Model\JsonWDL\Action\Arguments;
use GingerCore\Model\JsonWDL\Action\Name;
use GingerCore\Model\JsonWDL\Action\Type;
use GingerCore\Model\JsonWDL\Agent\AgentDescription;
use GingerCore\Model\JsonWDL\Agent\AgentOptions;
use GingerCore\Model\JsonWDL\ProcessStep;
use GingerCore\Model\JsonWDL\Role\RoleDescription;
use GingerCore\Model\JsonWDL\Role\RoleName;
use GingerCoreTest\TestCase;

/**
 * Class AgentDescriptionTest
 *
 * @package GingerCoreTest\Model\JsonWDL\Agent
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class AgentDescriptionTest extends TestCase
{
    /**
     * @var RoleDescription
     */
    private $role;

    /**
     * @var ActionDescription
     */
    private $actionDescription;

    /**
     * @var array
     */
    private $processStepCollection;


    protected function setUp()
    {
        $this->role = new RoleDescription(new RoleName('Testrole'));

        $this->actionDescription = ActionDescription::asQuery(new Name('testaction'));

        $this->processStepCollection = array(ProcessStep::fromRoleAndActionDescription($this->role, $this->actionDescription));
    }

    /**
     * @test
     */
    public function is_sequence()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::SEQUENCE,
            $this->processStepCollection
        );

        $this->assertTrue($agentDescription->isSequence());
    }

    /**
     * @test
     */
    public function is_split()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::SPLIT,
            $this->processStepCollection
        );

        $this->assertTrue($agentDescription->isSplit());
    }

    /**
     * @test
     */
    public function is_fork()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::FORK,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );

        $this->assertTrue($agentDescription->isFork());
    }

    /**
     * @test
     */
    public function is_loop()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );

        $this->assertTrue($agentDescription->isLoop());
    }

    /**
     * @test
     */
    public function is_valid_processSteps()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );

        $this->assertTrue($agentDescription->processSteps()[0]->roleDescription()->sameValueAs($this->role));
    }

    /**
     * @test
     */
    public function is_valid_options()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );

        $this->assertEquals('value', $agentDescription->options()->stringValue('option'));
    }

    /**
     * @test
     */
    public function is_same_value_as()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );
        $sameAgentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );

        $this->assertTrue($agentDescription->sameValueAs($sameAgentDescription));
    }

    /**
     * @test
     */
    public function is_not_same_value_with_other_type()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );
        $otherAgentDescription = new AgentDescription(
            AgentDescription::SEQUENCE,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );

        $this->assertFalse($agentDescription->sameValueAs($otherAgentDescription));
    }

    /**
     * @test
     */
    public function is_not_same_value_with_other_processSteps()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );

        $otherProcessStepCollection = array(ProcessStep::fromAgentDescription($agentDescription));

        $otherAgentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $otherProcessStepCollection,
            new AgentOptions(array('option' => 'value'))
        );

        $this->assertFalse($agentDescription->sameValueAs($otherAgentDescription));
    }

    /**
     * @test
     */
    public function is_not_same_value_with_other_options()
    {
        $agentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $this->processStepCollection,
            new AgentOptions(array('option' => 'value'))
        );
        $otherAgentDescription = new AgentDescription(
            AgentDescription::LOOP,
            $this->processStepCollection,
            new AgentOptions(array('param' => 'value'))
        );

        $this->assertFalse($agentDescription->sameValueAs($otherAgentDescription));
    }
} 