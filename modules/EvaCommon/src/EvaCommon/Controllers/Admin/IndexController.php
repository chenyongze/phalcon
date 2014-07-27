<?php

namespace Eva\EvaCommon\Controllers\Admin;

use Eva\EvaEngine\Mvc\Controller\AdminControllerBase as AdminControllerBase;
use Eva\EvaEngine\Mvc\Controller\AuthorityControllerInterface;

/**
* @resourceName Admin Entrance
* @resourceDescription
*/
class IndexController extends AdminControllerBase
{

    /**
    * @operationName Admin entrance redirect
    * @operationDescription
    */
    public function indexAction()
    {
        $this->view->disable();

        return $this->response->redirect('/admin/login');
    }

    /**
    * @operationName Admin dashboard
    * @operationDescription
    */
    public function dashboardAction()
    {
        $this->view->setModuleLayout('EvaCommon', '/views/admin/layouts/layout');
        $this->view->setModuleViewsDir('EvaCommon', '/views');
        $this->view->setModulePartialsDir('EvaCommon', '/views');
    }
}
