<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 30.01.14 - 20:26
 */

namespace GingerCore\Model\JsonWDL\Action;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;

class Type implements ValueObjectInterface
{
    const COMMAND = "Command";
    const QUERY   = "Query";

    /**
     * @var array
     */
    private $allowedTypes = array(self::COMMAND, self::QUERY);

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        Assertion::inArray($type, $this->allowedTypes, "Type is invalid");

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->type;
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
        if (!$other instanceof Type) {
            return false;
        }

        return $this->toString() === $other->toString();
    }
}