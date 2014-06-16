<?php

namespace Eva\EvaOAuthClient\Controllers;

class ControllerBase extends \Eva\EvaEngine\Mvc\Controller\ControllerBase
{
    public function initialize()
    {
        $this->view->setModuleLayout('EvaCommon', '/views/admin/layouts/login');
        $this->view->setModuleViewsDir('EvaOAuthClient', '/views');
        $this->view->setModulePartialsDir('EvaCommon', '/views');
    }
}
