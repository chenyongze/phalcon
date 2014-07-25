<?php

namespace Wscn\Events;

use Eva\EvaEngine\Exception;

class DispatchListener
{
    public function beforeExecuteRoute($event)
    {
        $dispatcher = $event->getSource();
        $config = $dispatcher->getDI()->getConfig();
        if(!$config->wscn->mobileRedirect) {
            return true;
        }

        $cookie = $dispatcher->getDI()->getCookies();
        //Disabled redirect from cookie
        if($cookie->has($config->wscn->mobileRedirectCookieName)) {
            return true;
        }

        $detect = new \Mobile_Detect();
        if($detect->isMobile()) {
            $controller = $dispatcher->getActiveController();
            $controllerClass = get_class($controller);
            $mobileControllerClass = str_replace('Wscn', 'WscnMobile', $controllerClass);
            $path = $dispatcher->getDI()->getRequest()->getURI();
            if(!class_exists($mobileControllerClass)) {
                $path = '/';
            }
            $mobileDomain = $config->wscn->mobileDomain;
            $redirectTo = "http://$mobileDomain$path";
            return $dispatcher->getDI()->getResponse()->redirect($redirectTo);
        }
    }
}
