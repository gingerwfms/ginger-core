<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 09.02.14 - 21:33
 */

namespace GingerCoreTest\Model\JsonWDL\Role;

use GingerCore\Model\JsonWDL\Role\RoleDescription;
use GingerCore\Model\JsonWDL\Role\RoleName;
use GingerCore\Model\JsonWDL\Role\RoleOptions;
use GingerCoreTest\TestCase;

/**
 * Class RoleDescriptionTest
 *
 * @package GingerCoreTest\Model\JsonWDL\RoleDescription
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class RoleDescriptionTest extends TestCase
{
    /**
     * @test
     */
    public function is_valid_name()
    {
        $role = new RoleDescription(new RoleName('Testrole'), new RoleOptions(array('option' => 'value')));

        $this->assertEquals('Testrole', $role->name()->toString());
    }

    /**
     * @test
     */
    public function is_valid_roleOptions()
    {
        $role = new RoleDescription(new RoleName('Testrole'), new RoleOptions(array('option' => 'value')));

        $this->assertEquals('value', $role->options()->stringValue('option'));
    }

    /**
     * @test
     */
    public function is_empty_roleOptions()
    {
        $role = new RoleDescription(new RoleName('Testrole'));

        $this->assertEquals('default', $role->options()->stringValue('option', 'default'));
        $this->assertEquals(array(), $role->options()->toArray());
    }

    /**
     * @test
     */
    public function is_same_value_as()
    {
        $role = new RoleDescription(new RoleName('Testrole'), new RoleOptions(array('option' => 'value')));
        $sameRole = new RoleDescription(new RoleName('Testrole'), new RoleOptions(array('option' => 'value')));

        $this->assertTrue($role->sameValueAs($sameRole));
    }

    /**
     * @test
     */
    public function is_not_same_value_with_different_name()
    {
        $role = new RoleDescription(new RoleName('Testrole'), new RoleOptions(array('option' => 'value')));
        $otherRole = new RoleDescription(new RoleName('Another name'), new RoleOptions(array('option' => 'value')));

        $this->assertFalse($role->sameValueAs($otherRole));
    }

    /**
     * @test
     */
    public function is_not_same_value_with_different_options()
    {
        $role = new RoleDescription(new RoleName('Testrole'), new RoleOptions(array('option' => 'value')));
        $otherRole = new RoleDescription(new RoleName('Testrole'), new RoleOptions(array('param' => 'value')));

        $this->assertFalse($role->sameValueAs($otherRole));
    }
} 