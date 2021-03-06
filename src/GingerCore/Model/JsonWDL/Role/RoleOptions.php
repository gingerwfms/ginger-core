<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 08.02.14 - 23:18
 */

namespace GingerCore\Model\JsonWDL\Role;

use Codeliner\Domain\Shared\ValueObjectInterface;
use GingerCore\Util\ArrayReader;

/**
 * Class RoleOptions
 *
 * @package GingerCore\Model\JsonWDL\RoleDescription
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class RoleOptions extends ArrayReader implements ValueObjectInterface
{
    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof RoleOptions) {
            return false;
        }

        return $this->toArray() === $other->toArray();
    }
}