<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.02.14 - 19:02
 */

namespace GingerCoreTest\Model\JsonWDL\Action;

use GingerCore\Model\JsonWDL\Action\ActionDescription;
use GingerCore\Model\JsonWDL\Action\Arguments;
use GingerCore\Model\JsonWDL\Action\EventDescription;
use GingerCore\Model\JsonWDL\Action\EventsDefinition;
use GingerCore\Model\JsonWDL\Action\Name;
use GingerCore\Model\JsonWDL\Action\StructureDefinition;
use GingerCore\Model\JsonWDL\Action\Type;
use GingerCoreTest\TestCase;

/**
 * Class ActionDescriptionTest
 *
 * @package GingerCoreTest\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ActionDescriptionTest extends TestCase
{
    /**
     * @test
     */
    public function is_command_factory_method_creating_valid_actionDescription_from_simple_types()
    {
        $command = ActionDescription::asCommand('Testcommand', array('CommandTriggered' => array('success' => 'boolean')));

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $command);

        $this->assertEquals('Testcommand', $command->name()->toString());
        $this->assertEquals(
            'success',
            $command->eventsDefinition()
                ->eventDescriptions()['CommandTriggered']
                ->structureDefinition()
                ->structureItems()[0]
                ->pathWithKey()
        );
    }

    /**
     * @test
     */
    public function is_command_factory_method_creating_valid_actionDescription_from_strong_types()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $command = ActionDescription::asCommand(new Name('Testcommand'), $eventsDefinition);

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $command);

        $this->assertEquals('Testcommand', $command->name()->toString());
        $this->assertEquals(
            'success',
            $command->eventsDefinition()
                ->eventDescriptions()['CommandTriggered']
                ->structureDefinition()
                ->structureItems()[0]
                ->pathWithKey()
        );
    }

    /**
     * @test
     */
    public function is_command_factory_method_creating_valid_actionDescription_with_arguments_from_simple_types()
    {
        $command = ActionDescription::asCommand(
            'Testcommand',
            array('CommandTriggered' => array('success' => 'boolean')),
            array('arg1' => 'some value')
        );

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $command);

        $this->assertEquals('Testcommand', $command->name()->toString());
        $this->assertEquals(
            'success',
            $command->eventsDefinition()
                ->eventDescriptions()['CommandTriggered']
                ->structureDefinition()
                ->structureItems()[0]
                ->pathWithKey()
        );

        $this->assertEquals('some value', $command->arguments()->argument('arg1'));
    }

    /**
     * @test
     */
    public function is_command_factory_method_creating_valid_actionDescription_with_arguments_from_strong_types()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $command = ActionDescription::asCommand(new Name('Testcommand'), $eventsDefinition, new Arguments(array('arg1' => 'some value')));

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $command);

        $this->assertEquals('Testcommand', $command->name()->toString());
        $this->assertEquals(
            'success',
            $command->eventsDefinition()
                ->eventDescriptions()['CommandTriggered']
                ->structureDefinition()
                ->structureItems()[0]
                ->pathWithKey()
        );

        $this->assertEquals('some value', $command->arguments()->argument('arg1'));
    }

    /**
     * @test
     */
    public function is_command_factory_method_creating_valid_actionDescription_with_structure_from_simple_types()
    {
        $command = ActionDescription::asCommand(
            'Testcommand',
            array('CommandTriggered' => array('success' => 'boolean')),
            array('arg1' => 'some value'),
            array('prop1' => 'string')
        );

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $command);

        $this->assertEquals('Testcommand', $command->name()->toString());
        $this->assertEquals(
            'success',
            $command->eventsDefinition()
                ->eventDescriptions()['CommandTriggered']
                ->structureDefinition()
                ->structureItems()[0]
                ->pathWithKey()
        );

        $this->assertEquals('some value', $command->arguments()->argument('arg1'));
        $this->assertEquals('prop1', $command->structureDefinition()->structureItems()[0]->pathWithKey());
    }

    /**
     * @test
     */
    public function is_command_factory_method_creating_valid_actionDescription_with_structure_from_strong_types()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $command = ActionDescription::asCommand(
            new Name('Testcommand'),
            $eventsDefinition,
            new Arguments(array('arg1' => 'some value')),
            StructureDefinition::fromPathValueTypeList(array('prop1' => 'string'))
        );

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $command);

        $this->assertEquals('Testcommand', $command->name()->toString());
        $this->assertEquals(
            'success',
            $command->eventsDefinition()
                ->eventDescriptions()['CommandTriggered']
                ->structureDefinition()
                ->structureItems()[0]
                ->pathWithKey()
        );

        $this->assertEquals('some value', $command->arguments()->argument('arg1'));
        $this->assertEquals('prop1', $command->structureDefinition()->structureItems()[0]->pathWithKey());
    }

    /**
     * @test
     */
    public function is_query_factory_method_creating_valid_actionDescription_from_simple_types()
    {
        $query = ActionDescription::asQuery('Testquery');

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $query);

        $this->assertEquals('Testquery', $query->name()->toString());
    }

    /**
     * @test
     */
    public function is_query_factory_method_creating_valid_actionDescription_from_strong_types()
    {
        $query = ActionDescription::asQuery(new Name('Testquery'));

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $query);

        $this->assertEquals('Testquery', $query->name()->toString());
    }

    /**
     * @test
     */
    public function is_query_factory_method_creating_valid_actionDescription_with_arguments_from_simple_types()
    {
        $query = ActionDescription::asQuery(
            'Testquery',
            array('arg1' => 'some value')
        );

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $query);

        $this->assertEquals('Testquery', $query->name()->toString());

        $this->assertEquals('some value', $query->arguments()->argument('arg1'));
    }

    /**
     * @test
     */
    public function is_query_factory_method_creating_valid_actionDescription_with_arguments_from_strong_types()
    {
        $query = ActionDescription::asQuery(new Name('Testquery'), new Arguments(array('arg1' => 'some value')));

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $query);

        $this->assertEquals('Testquery', $query->name()->toString());

        $this->assertEquals('some value', $query->arguments()->argument('arg1'));
    }

    /**
     * @test
     */
    public function is_query_factory_method_creating_valid_actionDescription_with_structure_from_simple_types()
    {
        $query = ActionDescription::asQuery(
            'Testquery',
            array('arg1' => 'some value'),
            array('prop1' => 'string')
        );

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $query);

        $this->assertEquals('Testquery', $query->name()->toString());

        $this->assertEquals('some value', $query->arguments()->argument('arg1'));
        $this->assertEquals('prop1', $query->structureDefinition()->structureItems()[0]->pathWithKey());
    }

    /**
     * @test
     */
    public function is_query_factory_method_creating_valid_actionDescription_with_structure_from_strong_types()
    {
        $query = ActionDescription::asQuery(
            new Name('Testquery'),
            new Arguments(array('arg1' => 'some value')),
            StructureDefinition::fromPathValueTypeList(array('prop1' => 'string'))
        );

        $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\ActionDescription', $query);

        $this->assertEquals('Testquery', $query->name()->toString());

        $this->assertEquals('some value', $query->arguments()->argument('arg1'));
        $this->assertEquals('prop1', $query->structureDefinition()->structureItems()[0]->pathWithKey());
    }

    /**
     * @test
     */
    public function is_valid_name()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $actionDescription = ActionDescription::asCommand(new Name('Testaction'), $eventsDefinition);

        $this->assertEquals('Testaction', $actionDescription->name()->toString());
    }

    /**
     * @test
     */
    public function is_valid_type()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $actionDescription = ActionDescription::asCommand(new Name('Testaction'), $eventsDefinition);

        $this->assertEquals(Type::COMMAND, $actionDescription->type()->toString());
    }

    /**
     * @test
     */
    public function has_structure_definition()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $command = ActionDescription::asCommand(
            new Name('Testcommand'),
            $eventsDefinition,
            new Arguments(array('arg1' => 'some value')),
            StructureDefinition::fromPathValueTypeList(array('prop1' => 'string'))
        );

        $this->assertTrue($command->hasStructureDefinition());
    }

    /**
     * @test
     */
    public function has_no_structure_definition()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $actionDescription = ActionDescription::asCommand(new Name('Testaction'), $eventsDefinition);

        $this->assertFalse($actionDescription->hasStructureDefinition());
    }

    /**
     * @test
     */
    public function is_same_value_as()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $actionDescription = ActionDescription::asCommand(new Name('Testaction'), $eventsDefinition);
        $sameActionDescription = ActionDescription::asCommand(new Name('Testaction'), $eventsDefinition);

        $this->assertTrue($actionDescription->sameValueAs($sameActionDescription));
    }

    /**
     * @test
     */
    public function is_not_same_value_as()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $actionDescription = ActionDescription::asCommand(new Name('Testaction'), $eventsDefinition);
        $otherActionDescription = ActionDescription::asCommand(new Name('Other action'), $eventsDefinition);

        $this->assertFalse($actionDescription->sameValueAs($otherActionDescription));
    }

    /**
     * @test
     */
    public function is_same_value_as_with_structure_definition()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $actionDescription = ActionDescription::asCommand(
            new Name('Testaction'),
            $eventsDefinition,
            new Arguments(array('foo' => 'bar')),
            StructureDefinition::fromPathValueTypeList(array('a' => StructureDefinition::STRING))
        );

        $sameActionDescription = ActionDescription::asCommand(
            new Name('Testaction'),
            $eventsDefinition,
            new Arguments(array('foo' => 'bar')),
            StructureDefinition::fromPathValueTypeList(array('a' => StructureDefinition::STRING))
        );

        $this->assertTrue($actionDescription->sameValueAs($sameActionDescription));
    }

    /**
     * @test
     */
    public function is_not_same_value_as_when_other_has_no_structure_description()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $actionDescription = ActionDescription::asCommand(
            new Name('Testaction'),
            $eventsDefinition,
            new Arguments(array('foo' => 'bar')),
            StructureDefinition::fromPathValueTypeList(array('a' => StructureDefinition::STRING))
        );

        $otherActionDescription = ActionDescription::asCommand(
            new Name('Testaction'),
            $eventsDefinition,
            new Arguments(array('foo' => 'bar'))
        );

        $this->assertFalse($actionDescription->sameValueAs($otherActionDescription));
    }

    /**
     * @test
     */
    public function is_not_same_value_as_when_only_other_has_structure_description()
    {
        $eventsDefinition = EventsDefinition::fromEventDescriptionDefinitions(array('CommandTriggered' => array('success' => 'boolean')));

        $actionDescription = ActionDescription::asCommand(
            new Name('Testaction'),
            $eventsDefinition,
            new Arguments(array('foo' => 'bar'))
        );

        $otherActionDescription = ActionDescription::asCommand(
            new Name('Testaction'),
            $eventsDefinition,
            new Arguments(array('foo' => 'bar')),
            StructureDefinition::fromPathValueTypeList(array('a' => StructureDefinition::STRING))
        );

        $this->assertFalse($actionDescription->sameValueAs($otherActionDescription));
    }
} 