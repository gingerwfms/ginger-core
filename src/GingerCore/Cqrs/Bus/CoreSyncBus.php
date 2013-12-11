<?php
/*
 * This file is part of the codeliner/ginger-wfms package.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GingerCore\Cqrs\Bus;

use Malocher\Cqrs\Bus\AbstractBus;
use GingerCore\Definition;
/**
 * Ginger Core CQRS CoreSyncBus
 * 
 * All messages send to this bus are published immediately
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class CoreSyncBus extends AbstractBus
{
    /**
     * @return string
     */
    public function getName()
    {
        return Definition::SYNC_BUS;
    }

}
