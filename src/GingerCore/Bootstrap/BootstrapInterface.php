<?php
/*
 * This file is part of the codeliner/ginger-core package.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GingerCore\Bootstrap;

use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Interface for a class that should bootstrap the Ginger WfMS system
 * 
 * The bootstrap class must be defined in the Ginger WfMS composer.json.
 * Use the "extra" key to configure the "bootstrap" and point to the Bootstrap class.
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface BootstrapInterface
{
    /**
     * Initialize the Ginger WfMS system
     */
    public static function init();
    
    /**
     * Get the ZF2 ServiceLocator
     * 
     * @return ServiceLocatorInterface
     */
    public static function getServiceManager();
}
