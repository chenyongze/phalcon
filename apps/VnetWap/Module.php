<?php

namespace VnetWap;

use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Eva\EvaEngine\Module\StandardInterface;

class Module implements ModuleDefinitionInterface, StandardInterface
{
    public static function registerGlobalAutoloaders()
    {
        return array(
            'VnetWap' => __DIR__ . '/src/VnetWap',
        );
    }

    public static function registerGlobalEventListeners()
    {
    }

    public static function registerGlobalViewHelpers()
    {
        return array(
            'wscnThumb' => 'VnetWap\View\Helpers\WscnThumb'
        );
    }

    public static function registerGlobalRelations()
    {
    }

    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders()
    {
    }

    /**
     * Registers the module-only services
     *
     * @param Phalcon\DI $di
     */
    public function registerServices($di)
    {
        $dispatcher = $di->getDispatcher();
        $dispatcher->setDefaultNamespace('VnetWap\Controllers');
    }

}
