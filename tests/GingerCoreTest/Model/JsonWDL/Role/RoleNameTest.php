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

namespace GingerCoreTest\Model\JsonWDL\Role;

use GingerCore\Model\JsonWDL\Role\RoleName;
use GingerCoreTest\TestCase;

/**
 * Class RoleNameTest
 *
 * @package GingerWorkflowEngineTest\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class RoleNameTest extends TestCase
{
    public function testToString()
    {
        $aName = new RoleName('Testname');

        $this->assertEquals('Testname', $aName->toString());
    }

    public function testSameValueAs()
    {
        $aName = new RoleName('Testname');
        $sameName = new RoleName('Testname');
        $otherName = new RoleName('Other Name');

        $this->assertTrue($aName->sameValueAs($sameName));
        $this->assertFalse($aName->sameValueAs($otherName));
    }
} 