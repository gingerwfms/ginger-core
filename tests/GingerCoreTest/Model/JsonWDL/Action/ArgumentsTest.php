<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 30.01.14 - 22:04
 */

namespace GingerCoreTest\Model\JsonWDL\Action;

use GingerCore\Model\JsonWDL\Action\Arguments;
use GingerCoreTest\TestCase;

/**
 * Class ArgumentsTest
 *
 * @package GingerWorkflowEngineTest\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ArgumentsTest extends TestCase
{
    public function testArgument()
    {
        $anArguments = new Arguments(array('foo' => 'bar'));

        $this->assertEquals('bar', $anArguments->argument('foo'));
        //test default
        $this->assertEquals('baz', $anArguments->argument('bat', 'baz'));
    }

    public function testToArray()
    {
        $argsArray = array('foo' => 'bar', 'bat' => 'baz');
        $anArguments = new Arguments($argsArray);

        $this->assertEquals($argsArray, $anArguments->toArray());
    }

    public function testToString()
    {
        $argsArray = array('foo' => 'bar', 'bat' => 'baz');
        $anArguments = new Arguments($argsArray);

        $this->assertEquals(json_encode($argsArray), $anArguments->toString());
    }

    public function testFromString()
    {
        $argsArray = array('foo' => 'bar');
        $anArguments = new Arguments($argsArray);
        $copyOfAnArguments = clone $anArguments;

        $otherArguments = $anArguments->fromString(json_encode(array('bat' => 'baz')));

        $this->assertTrue($anArguments->sameValueAs($copyOfAnArguments));
        $this->assertFalse($anArguments->sameValueAs($otherArguments));

        $this->assertEquals('baz', $otherArguments->argument('bat'));

    }
} 