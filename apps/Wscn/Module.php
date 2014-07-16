<?php

namespace Wscn;

use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Eva\EvaEngine\Module\StandardInterface;
use Eva\EvaEngine\Mvc\View;

class Module implements ModuleDefinitionInterface, StandardInterface
{
    public static function registerGlobalAutoloaders()
    {
        return array(
            'Wscn' => __DIR__ . '/src/Wscn',
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
        $dispatcher->setDefaultNamespace('Wscn\Controllers');
        View::registerComponent('post', 'Eva\EvaBlog\Components\Post');
    }

}
