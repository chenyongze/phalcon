<?php

namespace WscnApiVer1;

use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Eva\EvaEngine\Module\StandardInterface;
use Eva\EvaEngine\Error\ErrorHandler;

class Module implements ModuleDefinitionInterface, StandardInterface
{
    public static function registerGlobalAutoloaders()
    {
        return array(
            'WscnApiVer1' => __DIR__ . '/src/WscnApiVer1',
        );
    }

    public static function registerGlobalEventListeners()
    {
    }

    public static function registerGlobalViewHelpers()
    {
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
        $dispatcher->setDefaultNamespace('WscnApiVer1\Controllers');
        ErrorHandler::setErrorController('Jsonerror');
    }
}
