<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 06.02.14 - 21:17
 */

namespace GingerCore\Model\Workflow;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Class WorkflowId
 *
 * @package GingerWorkflowEngine\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowId implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        Assertion::string($id);
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->id;
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
        if (!$other instanceof WorkflowId) {
            return false;
        }

        return $this->toString() === $other->toString();
    }
}