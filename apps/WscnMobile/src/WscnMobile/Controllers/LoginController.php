<?php

namespace WscnMobile\Controllers;

use Eva\EvaUser\Models;
use Eva\EvaUser\Models\Login;
use Eva\EvaUser\Forms;

class LoginController extends ControllerBase
{
    public function indexAction()
    {
        $this->dispatcher->forward(array(
            'namespace' => 'Eva\EvaUser\Controllers',
            'controller' => 'login',
            'action' => 'index',
        ));
    }

    public function reactiveAction()
    {
        $this->dispatcher->forward(array(
            'namespace' => 'Eva\EvaUser\Controllers',
            'controller' => 'login',
            'action' => 'reactive',
        ));
    }
}
