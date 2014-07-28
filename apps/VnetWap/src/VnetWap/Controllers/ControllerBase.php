<?php

namespace VnetWap\Controllers;

class ControllerBase extends \Eva\EvaEngine\Mvc\Controller\ControllerBase
{
    public function initialize()
    {
        $this->view->setModuleLayout('VnetWap', '/views/layouts/default');
        $this->view->setModuleViewsDir('VnetWap', '/views');
        $this->view->setModulePartialsDir('VnetWap', '/views');
    }

}
