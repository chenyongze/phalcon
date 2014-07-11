<?php
// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-3 下午1:46
// +----------------------------------------------------------------------
// + Module.php
// +----------------------------------------------------------------------

namespace Eva\CounterRank;


use Eva\CounterRank\Utils\CounterRankUtil;
use Eva\CounterRank\Utils\JSClientHandlerUtil;
use Eva\EvaEngine\Module\StandardInterface;
use Phalcon\Loader,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface, StandardInterface
{

    /**
     * Register a specific autoloader for the module
     */
    public function registerAutoloaders()
    {
    }

    static public function registerGlobalEventListeners()
    {
    }

    public static function registerGlobalAutoloaders()
    {
        return array(
            'Eva\CounterRank' => __DIR__ . '/src/CounterRank'
        );
    }

    public static function registerGlobalViewHelpers()
    {
    }

    public static function registerGlobalRelations()
    {
    }

    /**
     * Register specific services for the module
     */
    public function registerServices($di)
    {
        $dispatcher = $di->getDispatcher();
        $dispatcher->setDefaultNamespace('Eva\CounterRank\Controllers');
        //Registering the view component
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir('/src/views/');
            return $view;
        });

    }
}