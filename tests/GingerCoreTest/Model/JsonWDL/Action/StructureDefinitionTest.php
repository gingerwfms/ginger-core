<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.02.14 - 21:14
 */

namespace GingerCoreTest\Model\JsonWDL\Action;

use GingerCore\Model\JsonWDL\Action\StructureDefinition;
use GingerCore\Model\JsonWDL\Action\StructureItem;
use GingerCoreTest\TestCase;

/**
 * Class StructureDefinitionTest
 *
 * @package GingerCoreTest\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class StructureDefinitionTest extends TestCase
{
    /**
     * @test
     */
    public function is_return_a_sorted_structureItems_collection()
    {
        $structureItemCollection = array(
            new StructureItem('b', StructureDefinition::STRING),
            new StructureItem('a.b', StructureDefinition::HASHTABLE),
            new StructureItem('a', StructureDefinition::HASHTABLE),
            new StructureItem('a.b.c', StructureDefinition::STRING)
        );

        $structureDefinition = new StructureDefinition($structureItemCollection);

        $sortedPaths = array('a', 'a.b', 'a.b.c', 'b');

        foreach($structureDefinition->structureItems() as $structureItem) {
            $this->assertInstanceOf('GingerCore\Model\JsonWDL\Action\StructureItem', $structureItem);
            $this->assertEquals(array_shift($sortedPaths), $structureItem->pathWithKey());
        }
    }

    /**
     * @test
     */
    public function is_sub_item_collection()
    {
        $structureItemCollection = array(
            new StructureItem('b', StructureDefinition::STRING),
            new StructureItem('a.b', StructureDefinition::HASHTABLE),
            new StructureItem('a', StructureDefinition::HASHTABLE),
            new StructureItem('a.b.c', StructureDefinition::STRING)
        );

        $structureDefinition = new StructureDefinition($structureItemCollection);

        $aStructureItem = new StructureItem('a', StructureDefinition::HASHTABLE);

        $subItemCollection = $structureDefinition->subStructureItemsOf($aStructureItem);

        $this->assertEquals(2, count($subItemCollection));

        foreach($subItemCollection as $subStructureItem) {
            $this->assertTrue($subStructureItem->isSubItemOf($aStructureItem));
        }
    }

    /**
     * @test
     */
    public function is_same_value_as()
    {
        $structureItemCollection = array(
            new StructureItem('b', StructureDefinition::STRING),
            new StructureItem('a.b', StructureDefinition::HASHTABLE),
            new StructureItem('a', StructureDefinition::HASHTABLE),
            new StructureItem('a.b.c', StructureDefinition::STRING)
        );

        $structureDefinition = new StructureDefinition($structureItemCollection);
        $sameStructureDefinition = new StructureDefinition($structureItemCollection);

        $this->assertTrue($structureDefinition->sameValueAs($sameStructureDefinition));
    }

    /**
     * @test
     */
    public function is_not_same_value_as()
    {
        $structureItemCollection = array(
            new StructureItem('b', StructureDefinition::STRING),
            new StructureItem('a.b', StructureDefinition::HASHTABLE),
            new StructureItem('a', StructureDefinition::HASHTABLE),
            new StructureItem('a.b.c', StructureDefinition::STRING)
        );

        $structureDefinition = new StructureDefinition($structureItemCollection);

        unset($structureItemCollection[0]);

        $structureItemCollection[] = new StructureItem('c', StructureDefinition::STRING);

        $otherStructureDefinition = new StructureDefinition($structureItemCollection);

        $this->assertFalse($structureDefinition->sameValueAs($otherStructureDefinition));
    }

    /**
     * @test
     */
    public function is_path_valueType_list()
    {
        $structureItemCollection = array(
            new StructureItem('b', StructureDefinition::STRING),
            new StructureItem('a.b', StructureDefinition::HASHTABLE),
            new StructureItem('a', StructureDefinition::HASHTABLE),
            new StructureItem('a.b.c', StructureDefinition::STRING)
        );

        $structureDefinition = new StructureDefinition($structureItemCollection);

        $this->assertEquals(
            array(
                'a'     => StructureDefinition::HASHTABLE,
                'a.b'   => StructureDefinition::HASHTABLE,
                'a.b.c' => StructureDefinition::STRING,
                'b'     => StructureDefinition::STRING,
            ),
            $structureDefinition->toArray()
        );
    }

    /**
     * @test
     */
    public function is_structure_definition_from_path_valueType_list()
    {
        $structureDefinition = StructureDefinition::fromPathValueTypeList(
            array(
                'a'     => StructureDefinition::HASHTABLE,
                'a.b'   => StructureDefinition::HASHTABLE,
                'a.b.c' => StructureDefinition::STRING,
                'b'     => StructureDefinition::STRING,
            )
        );

        $this->assertEquals(
            array(
                'a'     => StructureDefinition::HASHTABLE,
                'a.b'   => StructureDefinition::HASHTABLE,
                'a.b.c' => StructureDefinition::STRING,
                'b'     => StructureDefinition::STRING,
            ),
            $structureDefinition->toArray()
        );
    }
} 