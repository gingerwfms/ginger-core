<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.02.14 - 19:22
 */

namespace GingerCore\Model\JsonWDL\Action;

use Assert\Assertion;
use Codeliner\Comparison\EqualsBuilder;
use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Class StructureItem
 *
 * @package GingerCore\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class StructureItem implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $path;

    /**
     * @var string
     */
    private $valueType;

    /**
     * @param string $aPathWithKey
     * @param string $aValueType
     */
    public function __construct($aPathWithKey, $aValueType)
    {
        Assertion::string($aPathWithKey);

        $aPathWithKey = trim($aPathWithKey);

        Assertion::notEmpty($aPathWithKey);

        Assertion::inArray(
            $aValueType,
            array(
                StructureDefinition::BOOLEAN,
                StructureDefinition::INTEGER,
                StructureDefinition::FLOAT,
                StructureDefinition::STRING,
                StructureDefinition::COLLECTION,
                StructureDefinition::HASHTABLE
            )
        );

        $this->extractPathFromKey($aPathWithKey);

        $this->valueType = $aValueType;
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function pathKeys()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function path()
    {
        return implode('.', $this->path);
    }

    /**
     * @return string
     */
    public function pathWithKey()
    {
        if (empty($this->path)) {
            return $this->key();
        }

        return $this->path() . '.' . $this->key();
    }

    /**
     * @return string
     */
    public function valueType()
    {
        return $this->valueType;
    }

    /**
     * @param string $aPathWithKey
     */
    private function  extractPathFromKey($aPathWithKey)
    {
        $pathArray = explode('.', $aPathWithKey);

        $this->key = array_pop($pathArray);

        $this->path = $pathArray;
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if(!$other instanceof StructureItem) {
            return false;
        }

        return EqualsBuilder::create()
            ->append($this->key(), $other->key())
            ->append($this->pathKeys(), $other->pathKeys())
            ->append($this->valueType(), $other->valueType())
            ->strict()
            ->equals();
    }

    /**
     * @return bool
     */
    public function isStringValue()
    {
        return $this->valueType() === StructureDefinition::STRING;
    }

    /**
     * @return bool
     */
    public function isBooleanValue()
    {
        return $this->valueType() === StructureDefinition::BOOLEAN;
    }

    /**
     * @return bool
     */
    public function isIntegerValue()
    {
        return $this->valueType() === StructureDefinition::INTEGER;
    }

    /**
     * @return bool
     */
    public function isFloatValue()
    {
        return $this->valueType() === StructureDefinition::FLOAT;
    }

    /**
     * @return bool
     */
    public function isCollectionValue()
    {
        return $this->valueType() === StructureDefinition::COLLECTION;
    }

    /**
     * @return bool
     */
    public function isHashTableValue()
    {
        return $this->valueType() === StructureDefinition::HASHTABLE;
    }

    /**
     * @return bool
     */
    public function canHaveSubItems()
    {
        return $this->valueType() === StructureDefinition::COLLECTION || $this->valueType() === StructureDefinition::HASHTABLE;
    }

    /**
     * @param string|StructureItem $aPathOrStructureItem
     * @return bool
     */
    public function isSubItemOf($aPathOrStructureItem)
    {
        if ($aPathOrStructureItem instanceof StructureItem) {
            $aPath = $aPathOrStructureItem->pathWithKey();
        } else {
            Assertion::string($aPathOrStructureItem);

            $aPath = $aPathOrStructureItem;
        }

        return strpos($this->path(), $aPath) === 0;
    }

    /**
     * @param string $aKey
     * @param string $aValueType
     * @throws \RuntimeException
     * @return StructureItem
     */
    public function subItem($aKey, $aValueType)
    {
        Assertion::string($aKey);

        if ($this->valueType() === StructureDefinition::HASHTABLE) {
            return new StructureItem($this->pathWithKey() . '.' . $aKey, $aValueType);
        }

        if ($this->valueType() === StructureDefinition::COLLECTION) {
            return new StructureItem($this->pathWithKey() . '.$.' . $aKey, $aValueType);
        }

        throw new \RuntimeException('SubItems can not be created by Items with a scalar ValueType');
    }
}