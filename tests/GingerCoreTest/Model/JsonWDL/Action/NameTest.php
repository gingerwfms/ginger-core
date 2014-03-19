<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 31.01.14 - 20:09
 */

namespace GingerCoreTest\Model\JsonWDL\Action;

use GingerCore\Model\JsonWDL\Action\Name;
use GingerCoreTest\TestCase;

/**
 * Class NameTest
 *
 * @package GingerWorkflowEngineTest\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class NameTest extends TestCase
{
    public function testToString()
    {
        $aName = new Name('Testname');

        $this->assertEquals('Testname', $aName->toString());
    }

    public function testSameValueAs()
    {
        $aName = new Name('Testname');
        $sameName = new Name('Testname');
        $otherName = new Name('Other Name');

        $this->assertTrue($aName->sameValueAs($sameName));
        $this->assertFalse($aName->sameValueAs($otherName));
    }
} 