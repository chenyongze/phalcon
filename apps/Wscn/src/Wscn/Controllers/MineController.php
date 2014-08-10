<?php

namespace Wscn\Controllers;

use Eva\EvaUser\Models\Login;
use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;

class MineController extends ControllerBase implements SessionAuthorityControllerInterface
{
    public function dashboardAction()
    {
        $user = Login::getCurrentUser();
    }

}
