<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 09.02.14 - 22:17
 */

namespace GingerCoreTest\Model\JsonWDL;
use GingerCore\Model\JsonWDL\Action\ActionDescription;
use GingerCore\Model\JsonWDL\Action\Arguments;
use GingerCore\Model\JsonWDL\Action\Name;
use GingerCore\Model\JsonWDL\Action\Type;
use GingerCore\Model\JsonWDL\Agent\AgentDescription;
use GingerCore\Model\JsonWDL\ProcessStep;
use GingerCore\Model\JsonWDL\Role\RoleDescription;
use GingerCore\Model\JsonWDL\Role\RoleName;
use GingerCoreTest\TestCase;

/**
 * Class ProcessStepTest
 *
 * @package GingerCoreTest\Model\JsonWDL
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ProcessStepTest extends TestCase
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
     * @var AgentDescription
     */
    private $agentDescription;

    protected function setUp()
    {
        $this->role = new RoleDescription(new RoleName('Testrole'));

        $this->actionDescription = ActionDescription::asQuery(new Name('testaction'));

        $this->agentDescription = new AgentDescription(
            AgentDescription::SEQUENCE,
            array(ProcessStep::fromRoleAndActionDescription($this->role, $this->actionDescription))
        );
    }

    /**
     * @test
     */
    public function is_processStep_from_agentDescription()
    {
        $processStep = ProcessStep::fromAgentDescription($this->agentDescription);

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\ProcessStep', $processStep);
    }

    /**
     * @test
     */
    public function is_processStep_from_role_and_actionDescription()
    {
        $processStep = ProcessStep::fromRoleAndActionDescription($this->role, $this->actionDescription);

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\ProcessStep', $processStep);
    }

    /**
     * @test
     */
    public function is_agentDescription()
    {
        $processStep = ProcessStep::fromAgentDescription($this->agentDescription);

        $this->assertTrue($processStep->isAgentDescription());
    }

    /**
     * @test
     */
    public function is_not_agentDescription()
    {
        $processStep = ProcessStep::fromRoleAndActionDescription($this->role, $this->actionDescription);

        $this->assertFalse($processStep->isAgentDescription());
    }

    /**
     * @test
     */
    public function is_valid_agentDescription()
    {
        $processStep = ProcessStep::fromAgentDescription($this->agentDescription);

        $this->assertTrue($processStep->agentDescription()->sameValueAs($this->agentDescription));
    }

    /**
     * @test
     */
    public function is_valid_role()
    {
        $processStep = ProcessStep::fromRoleAndActionDescription($this->role, $this->actionDescription);

        $this->assertTrue($processStep->roleDescription()->sameValueAs($this->role));
    }

    /**
     * @test
     */
    public function is_valid_actionDescription()
    {
        $processStep = ProcessStep::fromRoleAndActionDescription($this->role, $this->actionDescription);

        $this->assertTrue($processStep->actionDescription()->sameValueAs($this->actionDescription));
    }

    /**
     * @test
     */
    public function is_same_value_as()
    {
        $processStep = ProcessStep::fromRoleAndActionDescription($this->role, $this->actionDescription);
        $sameProcessStep = ProcessStep::fromRoleAndActionDescription($this->role, $this->actionDescription);

        $this->assertTrue($processStep->sameValueAs($sameProcessStep));
    }

    /**
     * @test
     */
    public function is_not_same_value_as()
    {
        $processStep = ProcessStep::fromRoleAndActionDescription($this->role, $this->actionDescription);
        $otherProcessStep = ProcessStep::fromAgentDescription($this->agentDescription);

        $this->assertFalse($processStep->sameValueAs($otherProcessStep));
    }
} 