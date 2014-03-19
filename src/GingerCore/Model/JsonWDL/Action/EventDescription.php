<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 11.02.14 - 20:36
 */

namespace GingerCore\Model\JsonWDL\Action;

use Codeliner\Comparison\EqualsBuilder;
use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Class EventDescription
 *
 * @package GingerCore\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class EventDescription implements ValueObjectInterface
{
    /**
     * @var EventName
     */
    private $name;

    /**
     * @var StructureDefinition
     */
    private $structureDefinition;

    /**
     * @param string $anEventName
     * @param array $aStructurePathValueTypeList
     * @return EventDescription
     */
    public static function fromNameAndStructurePathValueTypeList($anEventName, array $aStructurePathValueTypeList)
    {
        return new static(new EventName($anEventName), StructureDefinition::fromPathValueTypeList($aStructurePathValueTypeList));
    }

    /**
     * @param EventName $anEventName
     * @param StructureDefinition $aStructureDefinition
     */
    public function __construct(EventName $anEventName, StructureDefinition $aStructureDefinition)
    {
        $this->name = $anEventName;
        $this->structureDefinition = $aStructureDefinition;
    }

    /**
     * @return EventName
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return StructureDefinition
     */
    public function structureDefinition()
    {
        return $this->structureDefinition;
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof EventDescription) {
            return false;
        }

        return EqualsBuilder::create()
            ->append($this->name()->sameValueAs($other->name()), true)
            ->append($this->structureDefinition()->sameValueAs($other->structureDefinition()), true)
            ->equals();
    }
}