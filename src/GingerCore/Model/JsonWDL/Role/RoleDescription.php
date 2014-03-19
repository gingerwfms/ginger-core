<?php
/*
 * This file is part of the codeliner/ginger-core.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 07.02.14 - 19:08
 */

namespace GingerCore\Model\JsonWDL\Role;

use Assert\Assertion;
use Codeliner\Comparison\EqualsBuilder;
use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Class RoleDescription
 *
 * @package GingerCore\Model\JsonWDL
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class RoleDescription implements ValueObjectInterface
{
    /**
     * @var RoleName
     */
    private $name;

    /**
     * @var RoleOptions
     */
    private $options;

    /**
     * @param RoleName $aName
     * @param RoleOptions|null $anOptions
     */
    public function __construct(RoleName $aName, RoleOptions $anOptions = null)
    {
        $this->name = $aName;

        if (is_null($anOptions)) {
            $this->options = new RoleOptions(array());
        } else {
            $this->options = $anOptions;
        }
    }

    /**
     * @return RoleName
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return RoleOptions
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
        if (!$other instanceof RoleDescription) {
            return false;
        }

        return EqualsBuilder::create()
            ->append($this->name()->sameValueAs($other->name()), true)
            ->append($this->options()->sameValueAs($other->options()), true)
            ->strict()
            ->equals();
    }
}