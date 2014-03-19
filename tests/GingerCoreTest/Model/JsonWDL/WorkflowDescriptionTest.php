<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 07.02.14 - 18:25
 */

namespace GingerCoreTest\Model\JsonWDL;

use GingerCore\Model\JsonWDL\Action\StructureDefinition;
use GingerCore\Model\JsonWDL\Action\Type;
use GingerCore\Model\JsonWDL\Agent\AgentDescription;
use GingerCore\Model\JsonWDL\WorkflowDescription;
use GingerCoreTest\TestCase;

/**
 * Class WorkflowDescriptionTest
 *
 * @package GingerCoreTest\Model\JsonWDL
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowDescriptionTest extends TestCase
{
    /**
     * @test
     */
    public function is_workflow_description_returned_by_factory_method_fromJson()
    {
        $arrayWD = array(
            'workflow' => '1234',
            'process' => array()
        );

        $workflowDescription = WorkflowDescription::fromJson(json_encode($arrayWD));

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\WorkflowDescription', $workflowDescription);
    }

    /**
     * @test
     */
    public function is_roleDescription_returned_by_factory_method_roleFromDefinition()
    {
        $roleDefinition = array(
            'name' => 'Testrole'
        );

        $roleDescription = WorkflowDescription::roleDescriptionFromDefinition($roleDefinition);

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Role\RoleDescription', $roleDescription);
        $this->assertEquals('Testrole', $roleDescription->name());
    }

    /**
     * @test
     */
    public function is_roleDescription_with_options_returned_by_factory_method_roleFromDefinition()
    {
        $roleDefinition = array(
            'name' => 'Testrole',
            'options' => array(
                'username' => 'cron'
            )
        );

        $roleDescription = WorkflowDescription::roleDescriptionFromDefinition($roleDefinition);

        $this->assertEquals('cron', $roleDescription->options()->stringValue('username'));
    }

    /**
     * @test
     */
    public function is_query_returned_by_factory_method_actionDescriptionFromDefinition()
    {
        $actionDescriptionDefinition = array(
            'name' => 'Testaction',
            'type' => Type::QUERY
        );

        $actionDescription = WorkflowDescription::actionDescriptionFromDefinition($actionDescriptionDefinition);

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $actionDescription);
        $this->assertEquals('Testaction', $actionDescription->name()->toString());
        $this->assertEquals(Type::QUERY, $actionDescription->type()->toString());
        $this->assertEquals(array(), $actionDescription->arguments()->toArray());
        $this->assertFalse($actionDescription->hasStructureDefinition());
    }

    /**
     * @test
     */
    public function is_query_with_arguments_returned_by_factory_method_actionDescriptionFromDefinition()
    {
        $actionDescriptionDefinition = array(
            'name' => 'Testaction',
            'type' => Type::QUERY,
            'arguments' => array(
                'foo' => 'bar'
            )
        );

        $actionDescription = WorkflowDescription::actionDescriptionFromDefinition($actionDescriptionDefinition);

        $this->assertEquals('bar', $actionDescription->arguments()->argument('foo'));
    }

    /**
     * @test
     */
    public function is_query_with_structureDefinition_returned_by_factory_method_actionDescriptionFromDefinition()
    {
        $actionDescriptionDefinition = array(
            'name' => 'Testaction',
            'type' => Type::QUERY,
            'structure' => array(
                'foo' => StructureDefinition::STRING
            )
        );

        $actionDescription = WorkflowDescription::actionDescriptionFromDefinition($actionDescriptionDefinition);


        $this->assertEquals('foo', $actionDescription->structureDefinition()->structureItems()[0]->key());
        $this->assertEquals(StructureDefinition::STRING, $actionDescription->structureDefinition()->structureItems()[0]->valueType());
    }

    /**
     * @test
     */
    public function is_command_returned_by_factory_method_actionDescriptionFromDefinition()
    {
        $actionDescriptionDefinition = array(
            'name' => 'Testaction',
            'type' => Type::COMMAND,
            'events' => array(
                'CommandTriggered' => array(
                    'property1' => 'string'
                )
            )
        );

        $actionDescription = WorkflowDescription::actionDescriptionFromDefinition($actionDescriptionDefinition);

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $actionDescription);
        $this->assertEquals('Testaction', $actionDescription->name()->toString());
        $this->assertEquals(Type::COMMAND, $actionDescription->type()->toString());
        $this->assertEquals(array(), $actionDescription->arguments()->toArray());
        $this->assertFalse($actionDescription->hasStructureDefinition());
        $this->assertEquals(
            'property1',
            $actionDescription->eventsDefinition()
                ->eventDescriptions()['CommandTriggered']
                ->structureDefinition()
                ->structureItems()[0]
                ->pathWithKey()
        );
    }

    /**
     * @test
     */
    public function is_command_with_arguments_returned_by_factory_method_actionDescriptionFromDefinition()
    {
        $actionDescriptionDefinition = array(
            'name' => 'Testaction',
            'type' => Type::COMMAND,
            'events' => array(
                'CommandTriggered' => array(
                    'property1' => 'string'
                )
            ),
            'arguments' => array(
                'foo' => 'bar'
            )
        );

        $actionDescription = WorkflowDescription::actionDescriptionFromDefinition($actionDescriptionDefinition);

        $this->assertEquals('bar', $actionDescription->arguments()->argument('foo'));
    }

    /**
     * @test
     */
    public function is_command_with_structureDefinition_returned_by_factory_method_actionDescriptionFromDefinition()
    {
        $actionDescriptionDefinition = array(
            'name' => 'Testaction',
            'type' => Type::COMMAND,
            'events' => array(
                'CommandTriggered' => array(
                    'property1' => 'string'
                )
            ),
            'structure' => array(
                'foo' => StructureDefinition::STRING
            )
        );

        $actionDescription = WorkflowDescription::actionDescriptionFromDefinition($actionDescriptionDefinition);


        $this->assertEquals('foo', $actionDescription->structureDefinition()->structureItems()[0]->key());
        $this->assertEquals(StructureDefinition::STRING, $actionDescription->structureDefinition()->structureItems()[0]->valueType());
    }

    /**
     * @test
     */
    public function is_role_action_processStep_returned_by_factory_method_processStepFromDefinition()
    {
        $processStepDefinition = array(
            'role' => array(
                'name' => 'Testrole'
            ),
            'action' => array(
                'name' => 'Testaction',
                'type' => Type::QUERY,
            )
        );

        $processStep = WorkflowDescription::processStepFromDefinition($processStepDefinition);

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\ProcessStep', $processStep);
        $this->assertFalse($processStep->isAgentDescription());
        $this->assertEquals('Testrole', $processStep->roleDescription()->name()->toString());
        $this->assertEquals('Testaction', $processStep->actionDescription()->name()->toString());
    }

    /**
     * @test
     */
    public function is_agentDescription_returned_by_factory_method_agentDescriptionFromDefinition()
    {
        $agentDescriptionDefinition = array(
            'type' => AgentDescription::SEQUENCE,
            'process' => array(
                array(
                    'role' => array(
                        'name' => 'Testrole'
                    ),
                    'action' => array(
                        'name' => 'Testaction',
                        'type' => Type::QUERY,
                    ),
                ),
            ),
        );

        $agentDescription = WorkflowDescription::agentDescriptionFromDefinition($agentDescriptionDefinition);

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Agent\AgentDescription', $agentDescription);

        $this->assertEquals(AgentDescription::SEQUENCE, $agentDescription->type());
        $this->assertEquals(1, count($agentDescription->processSteps()));

        $processStep = $agentDescription->processSteps()[0];

        $this->assertEquals('Testrole', $processStep->roleDescription()->name()->toString());
        $this->assertEquals('Testaction', $processStep->actionDescription()->name()->toString());
    }

    /**
     * @test
     */
    public function is_complex_workflowDescription_returned_by_factory_method_fromJson()
    {
        //this is an example workflow describing a possible sync between an ERP and a Shop frontend
        $workflowDescriptionDefinition = array(
            'workflow' => '1234',
            'process' => array(
                array(
                    //start with main sequence: copy all articles within the "books" category from ERP to Shop frontend
                    'agent' => array(
                        'type' => 'sequence',
                        'process' => array(
                            //query for books
                            array(
                                'role' => array(
                                    'name' => 'ERP_Articles'
                                ),
                                'action' => array(
                                    'name' => 'loadArticlesByCategory',
                                    'type' => 'query',
                                    'arguments' => array(
                                        'category' => 'books'
                                    ),
                                    'structure' => array(
                                        'id'                => 'integer',
                                        'articleNumber'     => 'string',
                                        'name'              => 'string',
                                        'price'             => 'float',
                                        'shortDescription'  => 'string',
                                        'longDescription'   => 'string',
                                        'images'            => 'collection',
                                        'images.$'          => 'hashtable',
                                        'images.$.name'     => 'string',
                                        'images.$.path'     => 'string',
                                    )
                                )
                            ),
                            //use the fork agent to parallelize the insertion of fetched articles in the shop
                            //and the conversion of the article pics into a web friendly format
                            array(
                                'agent' => array(
                                    'type' => 'fork',
                                    'process' => array(
                                        //this is the insert process
                                        array(
                                            'agent' => array(
                                                'type' => 'sequence',
                                                'process' => array(
                                                    //before inserting an article, filter out the ERP article id
                                                    //the shop assigns it's own auto incremented id
                                                    //the articles are referenced between the systems with their unique articleNumber
                                                    array(
                                                        'role' => array(
                                                            'name' => 'ItemFilter'
                                                        ),
                                                        'action' => array(
                                                            'name' => 'filterEachItem',
                                                            'type' => 'query',
                                                            'arguments' => array(
                                                                'propertyFilters' => array(
                                                                    'id'
                                                                )
                                                            ),
                                                            'structure' => array(
                                                                'articleNumber'     => 'string',
                                                                'name'              => 'string',
                                                                'price'             => 'float',
                                                                'shortDescription'  => 'string',
                                                                'longDescription'   => 'string',
                                                            )
                                                        )
                                                    ),
                                                    //Insert fetched and filtered ERP articles into the Shop
                                                    array(
                                                        'role' => array(
                                                            'name' => 'ShopArticleService',
                                                        ),
                                                        'action' => array(
                                                            'name' => 'insertArticles',
                                                            'type' => 'command',
                                                            'events' => array(
                                                                'ArticlesInserted' => array(
                                                                    'articleIds' => 'collection',
                                                                    'articleIds.$' => 'string'
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    ),
                                    //this is the picture convert process which runs in parallel to the articles insert process
                                    array(
                                        'agent' => array(
                                            'type' => 'sequence',
                                            'process' => array(
                                                array(
                                                    'role' => array(
                                                        'name' => 'ImageConverter',
                                                        'options' => array(
                                                            'engine' => 'imagemagick',
                                                        )
                                                    ),
                                                    'action' => array(
                                                        'name' => 'convertImages',
                                                        'type' => 'command',
                                                        'arguments' => array(
                                                            'structureItemName' => 'images.$.name',
                                                            'structureItemPath' => 'images.$.path',
                                                            'targetImageType'   => 'png',
                                                            'targetMaxWidth'    => 500,
                                                            'targetMaxHeight'   => 500,
                                                            'crop'              => true
                                                        ),
                                                        'events' => array(
                                                            'ImagesConverted' => array(
                                                                'images'        => 'collection',
                                                                'images.$'      => 'hashtable',
                                                                'images.$.name' => 'string',
                                                                'images.$.path' => 'string',
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            ),
                        )
                        //finished sequence: copy all articles within the "books" category from ERP to Shop frontend
                    )
                )
            )
        );

        $workflowDescription = WorkflowDescription::fromJson(json_encode($workflowDescriptionDefinition));

        $this->assertEquals('1234', $workflowDescription->workflowId()->toString());

        $this->assertEquals(1, count($workflowDescription->processSteps()));

        $mainProcessStep = $workflowDescription->processSteps()[0];

        $this->assertEquals(AgentDescription::SEQUENCE, $mainProcessStep->agentDescription()->type());

        $childProcessSteps = $mainProcessStep->agentDescription()->processSteps();

        $this->assertEquals(2, count($childProcessSteps));

        $firstChildProcessStep = $childProcessSteps[0];

        $this->assertEquals(AgentDescription::SEQUENCE, $firstChildProcessStep->agentDescription()->type());

        $this->assertEquals(1, count($firstChildProcessStep->agentDescription()->processSteps()));

        $firstChildOfFirstChildProcessStep = $firstChildProcessStep->agentDescription()->processSteps()[0];

        $this->assertEquals('ERP_Articles', $firstChildOfFirstChildProcessStep->roleDescription()->name());
        $this->assertEquals('loadArticlesByCategory', $firstChildOfFirstChildProcessStep->actionDescription()->name());

        $secondChildOfProcessStep = $mainProcessStep->agentDescription()->processSteps()[1];

        $this->assertEquals(AgentDescription::FORK, $secondChildOfProcessStep->agentDescription()->type());
    }
} 