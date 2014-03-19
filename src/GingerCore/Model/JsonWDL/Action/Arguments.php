<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 30.01.14 - 21:53
 */

namespace GingerCore\Model\JsonWDL\Action;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Class Aguments
 *
 * @package GingerWorkflowEngine\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Arguments implements ValueObjectInterface
{
    /**
     * @var array
     */
    private $arguments = array();

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function argument($key, $default = null)
    {
        if (isset($this->arguments[$key]) && !is_null($this->arguments[$key])) {
            return $this->arguments[$key];
        }

        return $default;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->arguments;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return json_encode($this->arguments);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param $argumentsString
     * @return Arguments
     */
    public function fromString($argumentsString)
    {
        Assertion::isJsonString($argumentsString, "ArgumentsString must be valid JSON Format");

        return new Arguments(json_decode($argumentsString, true));
    }


    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof Arguments) {
            return false;
        }

        return $this->toArray() == $other->toArray();
    }
}