<?php

namespace WscnMobile\Controllers;

use Eva\EvaUser\Models;
use Eva\EvaUser\Forms;

class RegisterController extends ControllerBase
{
    public function indexAction()
    {
        $this->dispatcher->forward(array(
            'namespace' => 'Eva\EvaUser\Controllers',
            'controller' => 'register',
            'action' => 'index',
        ));
    }

    public function checkAction()
    {
        $this->dispatcher->forward(array(
            'namespace' => 'Eva\EvaUser\Controllers',
            'controller' => 'register',
            'action' => 'check',
        ));
    }

}
