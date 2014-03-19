<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 11.02.14 - 20:44
 */

namespace GingerCoreTest\Model\JsonWDL\Action;

use GingerCore\Model\JsonWDL\Action\EventName;
use GingerCoreTest\TestCase;

/**
 * Class EventNameTest
 *
 * @package GingerCoreTest\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class EventNameTest extends TestCase
{
    public function testToString()
    {
        $aName = new EventName('Testname');

        $this->assertEquals('Testname', $aName->toString());
    }

    public function testSameValueAs()
    {
        $aName = new EventName('Testname');
        $sameName = new EventName('Testname');
        $otherName = new EventName('Other Name');

        $this->assertTrue($aName->sameValueAs($sameName));
        $this->assertFalse($aName->sameValueAs($otherName));
    }
} 