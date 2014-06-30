<?php

namespace Eva\EvaPermission\Events;

use Eva\EvaEngine\Exception;
use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;
use Eva\EvaPermission\Entities;
use Eva\EvaPermission\Auth;

class DispatchListener
{
    public function beforeExecuteRoute($event)
    {
        $dispatcher = $event->getSource();
        $controller = $dispatcher->getActiveController();
        //Not need to authority
        if(!($controller instanceof SessionAuthorityControllerInterface)) {
            return true;
        }

        $auth = new Auth\SessionAuthority();
        if(!$auth->checkAuth(get_class($controller), $dispatcher->getActionName())) {
            $dispatcher->setModuleName('EvaPermission');
            $dispatcher->setNamespaceName('Eva\EvaPermission\Controllers');
            $dispatcher->setControllerName('Error');
            $dispatcher->setActionName('index');
            $dispatcher->forward(array(
                "controller" => "error",
                "action" => "index",
                "params" => array(
                    'isAdminController' => true,
                    'redirectUri' => '',
                )
            ));
            return false;
        }

        return true;
    }
}
