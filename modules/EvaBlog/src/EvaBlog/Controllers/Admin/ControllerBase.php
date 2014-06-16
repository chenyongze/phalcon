<?php

namespace Eva\EvaBlog\Controllers\Admin;

class ControllerBase extends \Eva\EvaEngine\Mvc\Controller\ControllerBase
{
    public function initialize()
    {
        $this->view->setModuleLayout('EvaCommon', '/views/admin/layouts/layout');
        $this->view->setModuleViewsDir('EvaBlog', '/views');
        $this->view->setModulePartialsDir('EvaCommon', '/views');
    }
}
