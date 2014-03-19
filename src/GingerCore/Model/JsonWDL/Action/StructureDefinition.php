<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 07.02.14 - 21:34
 */

namespace GingerCore\Model\JsonWDL\Action;

use Assert\Assertion;
use Codeliner\Comparison\EqualsBuilder;
use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Class StructureDefinition
 *
 * @package GingerCore\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class StructureDefinition implements ValueObjectInterface
{
    const STRING     = 'string';
    const INTEGER    = 'integer';
    const FLOAT      = 'float';
    const BOOLEAN    = 'boolean';
    const COLLECTION = 'collection';
    const HASHTABLE  = 'hashtable';


    /**
     * @var StructureItem[]
     */
    private $structureItems = array();

    /**
     * @param array $aPathValueTypeList
     * @return StructureDefinition
     */
    public static function fromPathValueTypeList(array $aPathValueTypeList)
    {
        $structureItemCollection = array();

        foreach($aPathValueTypeList as $path => $valueType) {
            $structureItemCollection[] = new StructureItem($path, $valueType);
        }

        return new static($structureItemCollection);
    }

    /**
     * @param StructureItem[] $aStructureItemCollection
     * @throws \InvalidArgumentException
     */
    public function __construct(array $aStructureItemCollection)
    {
        Assertion::allIsInstanceOf($aStructureItemCollection, 'GingerCore\Model\JsonWDL\Action\StructureItem');

        foreach($aStructureItemCollection as $structureItem) {
            if (array_key_exists($structureItem->pathWithKey(), $this->structureItems)) {
                throw new \InvalidArgumentException(sprintf('Duplicate path detected: %s', $structureItem->pathWithKey()));
            }

            $this->structureItems[$structureItem->pathWithKey()] = $structureItem;
        }

        ksort($this->structureItems);
    }

    /**
     * @return StructureItem[]
     */
    public function structureItems()
    {
        return array_values($this->structureItems);
    }

    /**
     * @param $aPathOrStructureItem
     * @return StructureItem[]
     */
    public function subStructureItemsOf($aPathOrStructureItem)
    {
        if ($aPathOrStructureItem instanceof StructureItem) {
            $aPath = $aPathOrStructureItem->pathWithKey();
        } else {
            Assertion::string($aPathOrStructureItem);

            $aPath = $aPathOrStructureItem;
        }

        Assertion::keyExists($this->structureItems, $aPath, sprintf('Path not found in structure: %s', $aPath));

        $parentStructureItem = $this->structureItems[$aPath];

        if(!$parentStructureItem->canHaveSubItems()) {
            return array();
        }

        $subStructureItems = array();

        foreach($this->structureItems as $structureItem) {
            if($structureItem->isSubItemOf($parentStructureItem)) {
                $subStructureItems[] = $structureItem;
            }
        }

        return $subStructureItems;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $pathValueTypeList = array();

        foreach($this->structureItems() as $structureItem) {
            $pathValueTypeList[$structureItem->pathWithKey()] = $structureItem->valueType();
        }

        return $pathValueTypeList;
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof StructureDefinition) {
            return false;
        }

        if (count($this->structureItems()) !== count($other->structureItems())) {
            return false;
        }

        foreach($this->structureItems() as $i => $structureItem) {
            if(!$structureItem->sameValueAs($other->structureItems()[$i])) {
                return false;
            }
        }

        return true;
    }
}