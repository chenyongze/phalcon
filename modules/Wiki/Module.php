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

namespace Eva\Wiki;



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
            'Eva\Wiki' => __DIR__ . '/src/Wiki'
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
        $dispatcher->setDefaultNamespace('Eva\Wiki\Controllers');
        //Registering the view component
//        $di->set('view', function () {
//            $view = new View();
//            $view->setViewsDir('/views/');
//            return $view;
//        });

    }
}