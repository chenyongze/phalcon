<?php

namespace Eva\Wiki\Controllers\Admin;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-11 16:30
// +----------------------------------------------------------------------
// + ControllerBase.php
// +----------------------------------------------------------------------


use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;

class AdminControllerBase extends \Eva\EvaEngine\Mvc\Controller\AdminControllerBase implements SessionAuthorityControllerInterface
{
    public function initialize()
    {
        $this->view->setModuleLayout('EvaCommon', '/views/admin/layouts/layout');
        $this->view->setModuleViewsDir('Wiki', '/views');
        $this->view->setModulePartialsDir('EvaCommon', '/views');


    }
}
