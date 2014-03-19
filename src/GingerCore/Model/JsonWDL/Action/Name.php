<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 31.01.14 - 20:05
 */

namespace GingerCore\Model\JsonWDL\Action;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Class Name
 *
 * @package GingerWorkflowEngine\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Name implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        Assertion::string($name, "Action.name must be a string");
        Assertion::minLength($name, 5, "Action.name must be at least 5 chars long");

        $this->name = $name;
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
        if (!$other instanceof Name) {
            return false;
        }

        return $this->toString() === $other->toString();
    }
}