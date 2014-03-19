<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 11.02.14 - 20:34
 */

namespace GingerCore\Model\JsonWDL\Action;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;
use GingerCore\Util\Util;

/**
 * Class EventsDefinition
 *
 * @package GingerCore\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class EventsDefinition implements ValueObjectInterface
{
    /**
     * @var EventDescription[]
     */
    private $eventDescriptions = array();

    public static function fromEventDescriptionDefinitions(array $anEventDescriptionDefinitionCollection)
    {
        $eventDescriptions = array();

        foreach($anEventDescriptionDefinitionCollection as $eventName =>  $structurePathValueTypeList) {
            Assertion::string(
                $eventName,
                sprintf(
                    "EventName must be a string: %s given in EventDescriptionDefinitionCollection: %s",
                    Util::getType($eventName),
                    json_encode($anEventDescriptionDefinitionCollection)
                )
            );

            $eventDescriptions[$eventName] = EventDescription::fromNameAndStructurePathValueTypeList($eventName, $structurePathValueTypeList);
        }

        return new static($eventDescriptions);
    }

    /**
     * @param EventDescription[] $anEventDescriptionsCollection
     */
    public function __construct(array $anEventDescriptionsCollection)
    {
        Assertion::allIsInstanceOf($anEventDescriptionsCollection, 'GingerCore\Model\JsonWDL\Action\EventDescription');
        $this->eventDescriptions = $anEventDescriptionsCollection;
    }

    /**
     * @return EventDescription[]
     */
    public function eventDescriptions()
    {
        return $this->eventDescriptions;
    }

    public function toArray()
    {
        $eventDescriptionDefinitions = array();

        foreach($this->eventDescriptions() as $eventDescription) {
            $eventDescriptionDefinitions[$eventDescription->name()->toString()] = $eventDescription->structureDefinition()->toArray();
        }

        return $eventDescriptionDefinitions;
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof EventsDefinition) {
            return false;
        }

        if (count($this->eventDescriptions()) !== count($other->eventDescriptions())) {
            return false;
        }

        foreach($this->eventDescriptions() as $eventName => $eventDescription)
        {
            if(!isset($other->eventDescriptions()[$eventName])) {
                return false;
            }

            if (!$eventDescription->sameValueAs($other->eventDescriptions()[$eventName])) {
                return false;
            }
        }

        return true;
    }
}