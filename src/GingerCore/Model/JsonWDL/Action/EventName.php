<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 11.02.14 - 20:38
 */

namespace GingerCore\Model\JsonWDL\Action;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;
use GingerCore\Util\Util;

/**
 * Class EventName
 *
 * @package GingerCore\Model\JsonWDL\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class EventName implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $name;

    public function __construct($aName)
    {
        Assertion::string($aName, sprintf("An EventName must be a string %s given", Util::getType($aName)));
        Assertion::minLength($aName, 5, sprintf("An EventName must be at least 5 chars long, less given in: %s", $aName));

        $this->name = $aName;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof EventName) {
            return false;
        }

        return $this->toString() === $other->toString();
    }
}