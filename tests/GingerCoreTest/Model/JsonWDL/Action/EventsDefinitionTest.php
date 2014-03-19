<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 11.02.14 - 21:23
 */

namespace GingerCoreTest\Model\JsonWDL\Action;

use GingerCore\Model\JsonWDL\Action\EventsDefinition;
use GingerCoreTest\TestCase;

/**
 * Class EventsDefinitionTest
 *
 * @package GingerCoreTest\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class EventsDefinitionTest extends TestCase
{
    /**
     * @test
     */
    public function is_factory_method_returning_a_valid_events_definition()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(
            array(
                'Testevent' => array(
                    'property1' => 'string'
                )
            )
        );

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\EventsDefinition', $eventsDefinition);

        $this->assertEquals(1, count($eventsDefinition->eventDescriptions()));
        $this->assertEquals('Testevent', $eventsDefinition->eventDescriptions()['Testevent']->name()->toString());
        $this->assertEquals('property1', $eventsDefinition->eventDescriptions()['Testevent']->structureDefinition()->structureItems()[0]->pathWithKey());
    }

    /**
     * @test
     */
    public function is_toArray_returning_valid_definition()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(
            array(
                'Testevent' => array(
                    'property1' => 'string'
                )
            )
        );

        $check = array(
            'Testevent' => array(
                'property1' => 'string'
            )
        );

        $this->assertEquals($check, $eventsDefinition->toArray());
    }

    /**
     * @test
     */
    public function is_same_value_as()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(
            array(
                'Testevent' => array(
                    'property1' => 'string'
                )
            )
        );

        $sameEventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(
            array(
                'Testevent' => array(
                    'property1' => 'string'
                )
            )
        );

        $this->assertTrue($eventsDefinition->sameValueAs($sameEventsDefinition));
    }

    /**
     * @test
     */
    public function is_not_same_value_as()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(
            array(
                'Testevent' => array(
                    'property1' => 'string'
                )
            )
        );

        $otherEventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(
            array(
                'SomeEvent' => array(
                    'property1' => 'string'
                )
            )
        );

        $this->assertFalse($eventsDefinition->sameValueAs($otherEventsDefinition));
    }
} 