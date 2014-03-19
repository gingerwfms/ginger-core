<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 30.01.14 - 20:52
 */

namespace GingerCoreTest\Model\JsonWDL\Action;

use GingerCore\Model\JsonWDL\Action\Type;
use GingerCoreTest\TestCase;

/**
 * Class TypeTest
 *
 * @package GingerWorkflowEngineTest\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class TypeTest extends TestCase
{
    public function testToString()
    {
        $type = new Type(Type::COMMAND);

        $this->assertEquals('Command', $type->toString());
    }

    public function testSameValueAs()
    {
        $command      = new Type(Type::COMMAND);
        $otherCommand = new Type(Type::COMMAND);
        $query        = new Type(Type::QUERY);

        $this->assertTrue($command->sameValueAs($otherCommand));
        $this->assertFalse($command->sameValueAs($query));
    }
} 