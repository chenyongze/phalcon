<?php

namespace WscnMobile\Controllers;

class ControllerBase extends \Eva\EvaEngine\Mvc\Controller\ControllerBase
{
    public function initialize()
    {
        $this->view->setModuleLayout('WscnMobile', '/views/layouts/default');
        $this->view->setModuleViewsDir('WscnMobile', '/views');
        $this->view->setModulePartialsDir('WscnMobile', '/views');
    }

}
