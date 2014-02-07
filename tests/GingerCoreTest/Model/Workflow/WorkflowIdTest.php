<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 06.02.14 - 21:24
 */

namespace GingerCoreTest\Model\Workflow;

use GingerCore\Model\Workflow\WorkflowId;
use GingerCoreTest\TestCase;

/**
 * Class WorkflowIdTest
 *
 * @package GingerWorkflowEngineTest\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowIdTest extends TestCase
{
    /**
     * @test
     */
    public function is_id_equal_to_string_representation_of_workflow_id()
    {
        $id = '1234';

        $workflowId = new WorkflowId($id);

        $this->assertEquals($id, $workflowId->toString());
    }

    /**
     * @test
     */
    public function are_to_workflow_ids_with_equal_value_the_same()
    {
        $workflowId = new WorkflowId('1234');

        $sameWorkflowId = new WorkflowId('1234');

        $this->assertTrue($workflowId->sameValueAs($sameWorkflowId));
    }

    /**
     * @test
     */
    public function are_to_workflow_ids_with_different_values_not_the_same()
    {
        $workflowId = new WorkflowId('1234');

        $otherWorkflowId = new WorkflowId('5678');

        $this->assertFalse($workflowId->sameValueAs($otherWorkflowId));
    }
} 