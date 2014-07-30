<?php

namespace VnetWap\Controllers;

class ControllerBase extends \Eva\EvaEngine\Mvc\Controller\ControllerBase
{
    public function beforeExecuteRoute()
    {
        $cacheKey = 'vnetwap-' . md5($this->request->getURI());
        $this->view->cache(array(
            'lifetime' => 60,
            'key' => $cacheKey,
        ));
        if($this->view->getCache()->exists($cacheKey)) {
            return false;
        }
    }

    public function initialize()
    {
        $this->view->setModuleLayout('VnetWap', '/views/layouts/default');
        $this->view->setModuleViewsDir('VnetWap', '/views');
        $this->view->setModulePartialsDir('VnetWap', '/views');
    }

}
