<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 07.02.14 - 19:47
 */

namespace GingerCore\Model\JsonWDL\Action;

use Codeliner\Comparison\EqualsBuilder;
use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Class ActionDescription
 *
 * @package GingerCore\Model\JsonWDL
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ActionDescription implements ValueObjectInterface
{
    /**
     * @var Name
     */
    private $name;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var EventsDefinition
     */
    private $eventsDefinition;

    /**
     * @var Arguments
     */
    private $arguments;

    /**
     * @var StructureDefinition
     */
    private $structureDefinition;

    /**
     * @param string|Name                    $aName
     * @param array|EventsDefinition         $anEventsDefinition
     * @param null|array|Arguments           $anArguments
     * @param null|array|StructureDefinition $aStructureDefinition
     * @return ActionDescription
     */
    public static function asCommand($aName, $anEventsDefinition, $anArguments = null, $aStructureDefinition = null)
    {
        if (is_string($aName)) {
            $aName = new Name($aName);
        }

        if (!$anEventsDefinition instanceof EventsDefinition) {
            $anEventsDefinition = EventsDefinition::fromEventDescriptionDefinitions($anEventsDefinition);
        }

        if (is_null($anArguments)) {
            $anArguments = new Arguments(array());
        }

        if (!$anArguments instanceof Arguments) {
            $anArguments = new Arguments($anArguments);
        }

        if (is_array($aStructureDefinition)) {
            $aStructureDefinition = StructureDefinition::fromPathValueTypeList($aStructureDefinition);
        }

        return new static($aName, new Type(Type::COMMAND), $anArguments, $aStructureDefinition, $anEventsDefinition);
    }

    /**
     * @param string|Name                    $aName
     * @param null|array|Arguments           $anArguments
     * @param null|array|StructureDefinition $aStructureDefinition
     * @return ActionDescription
     */
    public static function asQuery($aName, $anArguments = null, $aStructureDefinition = null)
    {
        if (is_string($aName)) {
            $aName = new Name($aName);
        }

        if (is_null($anArguments)) {
            $anArguments = new Arguments(array());
        }

        if (!$anArguments instanceof Arguments) {
            $anArguments = new Arguments($anArguments);
        }

        if (is_array($aStructureDefinition)) {
            $aStructureDefinition = StructureDefinition::fromPathValueTypeList($aStructureDefinition);
        }

        return new static($aName, new Type(Type::QUERY), $anArguments, $aStructureDefinition);
    }

    /**
     * @param Name                $aName
     * @param Type                $aType
     * @param Arguments           $anArguments
     * @param StructureDefinition $aStructureDefinition
     * @param EventsDefinition    $anEventsDefinition
     */
    private function __construct(
        Name $aName,
        Type $aType,
        Arguments $anArguments,
        StructureDefinition $aStructureDefinition = null,
        EventsDefinition $anEventsDefinition = null)
    {
        $this->name                 = $aName;
        $this->type                 = $aType;
        $this->eventsDefinition     = $anEventsDefinition;
        $this->arguments            = $anArguments;
        $this->structureDefinition  = $aStructureDefinition;
    }

    /**
     * @return Name
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return Type
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return EventsDefinition
     */
    public function eventsDefinition()
    {
        return $this->eventsDefinition;
    }

    /**
     * @return Arguments
     */
    public function arguments()
    {
        return $this->arguments;
    }

    /**
     * @return StructureDefinition
     */
    public function structureDefinition()
    {
        return $this->structureDefinition;
    }

    /**
     * @return bool
     */
    public function hasStructureDefinition()
    {
        return !is_null($this->structureDefinition);
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof ActionDescription) {
            return false;
        }

        if ($this->hasStructureDefinition()) {
            if (!$other->hasStructureDefinition()) {
                return false;
            }

            if (!$this->structureDefinition()->sameValueAs($other->structureDefinition())) {
                return false;
            }
        }

        if (!$this->hasStructureDefinition() && $other->hasStructureDefinition()) {
            return false;
        }

        if (!is_null($this->eventsDefinition()) && !is_null($other->eventsDefinition())) {
            if (!$this->eventsDefinition()->sameValueAs($other->eventsDefinition())) {
                return false;
            }
        } else if (!is_null($this->eventsDefinition()) && is_null($other->eventsDefinition())) {
            return false;
        } else if (is_null($this->eventsDefinition()) && !is_null($other->eventsDefinition())) {
            return false;
        }

        return EqualsBuilder::create()
            ->append($this->name()->sameValueAs($other->name()), true)
            ->append($this->type()->sameValueAs($other->type()), true)
            ->append($this->arguments()->sameValueAs($other->arguments()), true)
            ->strict()
            ->equals();
    }
}