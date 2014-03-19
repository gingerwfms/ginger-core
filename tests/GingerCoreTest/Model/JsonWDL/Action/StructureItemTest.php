<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.02.14 - 19:34
 */

namespace GingerCoreTest\Model\JsonWDL\Action;

use GingerCore\Model\JsonWDL\Action\StructureDefinition;
use GingerCore\Model\JsonWDL\Action\StructureItem;
use GingerCoreTest\TestCase;

/**
 * Class StructureItemTest
 *
 * @package GingerCoreTest\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class StructureItemTest extends TestCase
{
    /**
     * @test
     */
    public function is_valid_key()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertEquals('key', $structureItem->key());
    }

    /**
     * @test
     */
    public function is_valid_pathKeys()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertEquals(array('a', 'path', 'with'), $structureItem->pathKeys());
    }

    /**
     * @test
     */
    public function is_empty_pathKeys()
    {
        $structureItem = new StructureItem('onlyAKey', StructureDefinition::STRING);

        $this->assertEquals(array(), $structureItem->pathKeys());
    }

    /**
     * @test
     */
    public function is_valid_path()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertEquals('a.path.with', $structureItem->path());
    }

    /**
     * @test
     */
    public function is_empty_path_when_only_key_is_given()
    {
        $structureItem = new StructureItem('onlyAKey', StructureDefinition::STRING);

        $this->assertEquals('', $structureItem->path());
    }

    /**
     * @test
     */
    public function is_valid_path_with_key()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertEquals('a.path.with.key', $structureItem->pathWithKey());
    }

    /**
     * @test
     */
    public function is_key_when_no_path_is_given()
    {
        $structureItem = new StructureItem('onlyAKey', StructureDefinition::STRING);

        $this->assertEquals('onlyAKey', $structureItem->pathWithKey());
    }

    /**
     * @test
     */
    public function is_valid_valueType()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertEquals('string', $structureItem->valueType());
    }

    /**
     * @test
     */
    public function is_same_value_as()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);
        $sameStructureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertTrue($structureItem->sameValueAs($sameStructureItem));
    }

    /**
     * @test
     */
    public function is_not_same_value_as()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);
        $otherStructureItem = new StructureItem('a.path.with.key', StructureDefinition::BOOLEAN);

        $this->assertFalse($structureItem->sameValueAs($otherStructureItem));
    }

    /**
     * @test
     */
    public function is_string_value()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertTrue($structureItem->isStringValue());
    }

    /**
     * @test
     */
    public function is_boolean_value()
    {
        $structureItem = new StructureItem('a.key', StructureDefinition::BOOLEAN);

        $this->assertTrue($structureItem->isBooleanValue());
    }

    /**
     * @test
     */
    public function is_integer_value()
    {
        $structureItem = new StructureItem('a.key', StructureDefinition::INTEGER);

        $this->assertTrue($structureItem->isIntegerValue());
    }

    /**
     * @test
     */
    public function is_float_value()
    {
        $structureItem = new StructureItem('a.key', StructureDefinition::FLOAT);

        $this->assertTrue($structureItem->isFloatValue());
    }

    /**
     * @test
     */
    public function is_collection_value()
    {
        $structureItem = new StructureItem('a.key', StructureDefinition::COLLECTION);

        $this->assertTrue($structureItem->isCollectionValue());
    }

    /**
     * @test
     */
    public function is_hash_table_value()
    {
        $structureItem = new StructureItem('a.key', StructureDefinition::HASHTABLE);

        $this->assertTrue($structureItem->isHashTableValue());
    }

    /**
     * @test
     */
    public function is_canHaveSubItems_true_for_hash_table()
    {
        $structureItem = new StructureItem('a.key', StructureDefinition::HASHTABLE);

        $this->assertTrue($structureItem->canHaveSubItems());
    }

    /**
     * @test
     */
    public function is_canHaveSubItems_true_for_collection()
    {
        $structureItem = new StructureItem('a.key', StructureDefinition::COLLECTION);

        $this->assertTrue($structureItem->canHaveSubItems());
    }

    /**
     * @test
     */
    public function is_canHaveSubItems_false_for_scalar_valueTypes()
    {
        $structureItem = new StructureItem('a.key', StructureDefinition::STRING);

        $this->assertFalse($structureItem->canHaveSubItems());

        $structureItem = new StructureItem('a.key', StructureDefinition::INTEGER);

        $this->assertFalse($structureItem->canHaveSubItems());

        $structureItem = new StructureItem('a.key', StructureDefinition::FLOAT);

        $this->assertFalse($structureItem->canHaveSubItems());

        $structureItem = new StructureItem('a.key', StructureDefinition::BOOLEAN);

        $this->assertFalse($structureItem->canHaveSubItems());
    }

    /**
     * @test
     */
    public function is_sub_item_of_a_path()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertTrue($structureItem->isSubItemOf('a'));

        $this->assertTrue($structureItem->isSubItemOf('a.path'));

        $this->assertTrue($structureItem->isSubItemOf('a.path.with'));
    }

    /**
     * @test
     */
    public function is_sub_item_of_a_parent_item()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $parentStructureItem = new StructureItem('a.path.with', StructureDefinition::HASHTABLE);

        $this->assertTrue($structureItem->isSubItemOf($parentStructureItem));
    }

    /**
     * @test
     */
    public function is_not_sub_item_of_different_path()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertFalse($structureItem->isSubItemOf('another.path'));
    }

    /**
     * @test
     */
    public function is_not_sub_item_of_another_parent_item()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $parentStructureItem = new StructureItem('another.path', StructureDefinition::HASHTABLE);

        $this->assertFalse($structureItem->isSubItemOf($parentStructureItem));
    }

    /**
     * @test
     */
    public function is_not_sub_item_of_own_path()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::STRING);

        $this->assertFalse($structureItem->isSubItemOf('a.path.with.key'));
    }

    /**
     * @test
     */
    public function is_valid_sub_item_from_hash_table()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::HASHTABLE);

        $cloneOfStructureItem = clone $structureItem;

        $subItem = $structureItem->subItem('subitem', StructureDefinition::STRING);

        $this->assertInstanceOf(get_class($structureItem), $subItem);

        $this->assertTrue($subItem->isSubItemOf($structureItem));

        $this->assertTrue($structureItem->sameValueAs($cloneOfStructureItem));
    }

    /**
     * @test
     */
    public function is_valid_sub_item_from_collection()
    {
        $structureItem = new StructureItem('a.path.with.key', StructureDefinition::COLLECTION);

        $cloneOfStructureItem = clone $structureItem;

        $subItem = $structureItem->subItem('subitem', StructureDefinition::STRING);

        $this->assertInstanceOf(get_class($structureItem), $subItem);

        $this->assertTrue($subItem->isSubItemOf($structureItem));

        $this->assertEquals('a.path.with.key.$', $subItem->path());

        $this->assertTrue($structureItem->sameValueAs($cloneOfStructureItem));
    }
} 