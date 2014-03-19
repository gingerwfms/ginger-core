<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 07.02.14 - 19:13
 */

namespace GingerCore\Model\JsonWDL\Agent;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;
use GingerCore\Model\JsonWDL\ProcessStep;

/**
 * Class AgentDescription
 *
 * @package GingerCore\Model\JsonWDL
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class AgentDescription implements ValueObjectInterface
{
    const SEQUENCE = 'sequence';
    const SPLIT    = 'split';
    const FORK     = 'fork';
    const LOOP     = 'loop';

    /**
     * @var string
     */
    private $type;

    /**
     * @var ProcessStep[]
     */
    private $processSteps;

    /**
     * @var AgentOptions
     */
    private $options;

    /**
     * @param string        $aType
     * @param array         $aProcessStepCollection
     * @param AgentOptions  $anOptions
     */
    public function __construct($aType, array $aProcessStepCollection, AgentOptions $anOptions = null)
    {
        Assertion::inArray($aType, array(self::SEQUENCE, self::SPLIT, self::FORK, self::LOOP));
        Assertion::allIsInstanceOf($aProcessStepCollection, 'GingerCore\Model\JsonWDL\ProcessStep');

        $this->type = $aType;
        $this->processSteps = $aProcessStepCollection;

        $this->options = (is_null($anOptions))? new AgentOptions(array()) : $anOptions;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return ProcessStep[]
     */
    public function processSteps()
    {
        return $this->processSteps;
    }

    /**
     * @return bool
     */
    public function isSequence()
    {
        return $this->type === self::SEQUENCE;
    }

    /**
     * @return bool
     */
    public function isSplit()
    {
        return $this->type === self::SPLIT;
    }

    /**
     * @return bool
     */
    public function isFork()
    {
        return $this->type === self::FORK;
    }

    /**
     * @return bool
     */
    public function isLoop()
    {
        return $this->type === self::LOOP;
    }

    /**
     * @return AgentOptions
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof AgentDescription) {
            return false;
        }

        if (count($this->processSteps()) != count($other->processSteps())) {
            return false;
        }

        if ($this->type() !== $other->type()) {
            return false;
        }

        if (!$this->options()->sameValueAs($other->options())) {
            return false;
        }

        foreach($this->processSteps() as $i => $processStep) {
            if (!$processStep->sameValueAs($other->processSteps()[$i])) {
                return false;
            }
        }

        return true;
    }
}