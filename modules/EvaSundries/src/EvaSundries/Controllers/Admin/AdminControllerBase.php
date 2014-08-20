<?php

namespace Eva\EvaSundries\Controllers\Admin;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-18 15:59
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;

class AdminControllerBase extends \Eva\EvaEngine\Mvc\Controller\AdminControllerBase implements SessionAuthorityControllerInterface
{
    public function initialize()
    {
        $this->view->setModuleLayout('EvaCommon', '/views/admin/layouts/layout');
        $this->view->setModuleViewsDir('EvaSundries', '/views');
        $this->view->setModulePartialsDir('EvaCommon', '/views');
    }
} 