<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 09.02.14 - 21:42
 */

namespace GingerCore\Model\JsonWDL\Agent;

use Codeliner\Domain\Shared\ValueObjectInterface;
use GingerCore\Util\ArrayReader;

/**
 * Class AgentOptions
 *
 * @package GingerCore\Model\JsonWDL\Agent
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class AgentOptions extends ArrayReader implements ValueObjectInterface
{
    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof AgentOptions) {
            return false;
        }

        return $this->toArray() === $other->toArray();
    }
}