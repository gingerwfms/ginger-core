<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 11.02.14 - 21:02
 */

namespace GingerCoreTest\Model\JsonWDL\Action;

use GingerCore\Model\JsonWDL\Action\EventDescription;
use GingerCoreTest\TestCase;

/**
 * Class EventDescriptionTest
 *
 * @package GingerCoreTest\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class EventDescriptionTest extends TestCase
{
    /**
     * @test
     */
    public function is_factory_method_returning_a_valid_event_description()
    {
        $eventDescription = EventDescription::fromNameAndStructurePathValueTypeList('Eventname', array('property' => 'string'));

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\EventDescription', $eventDescription);

        $this->assertEquals('Eventname', $eventDescription->name()->toString());
        $this->assertEquals('property', $eventDescription->structureDefinition()->structureItems()[0]->pathWithKey());
    }

    /**
     * @test
     */
    public function is_same_value_as()
    {
        $eventDescription = EventDescription::fromNameAndStructurePathValueTypeList('Eventname', array('property' => 'string'));
        $sameEventDescription = EventDescription::fromNameAndStructurePathValueTypeList('Eventname', array('property' => 'string'));

        $this->assertTrue($eventDescription->sameValueAs($sameEventDescription));
    }

    /**
     * @test
     */
    public function is_not_same_value_as()
    {
        $eventDescription = EventDescription::fromNameAndStructurePathValueTypeList('Eventname', array('property' => 'string'));
        $otherEventDescription = EventDescription::fromNameAndStructurePathValueTypeList('Other', array('property' => 'string'));

        $this->assertFalse($eventDescription->sameValueAs($otherEventDescription));
    }
} 