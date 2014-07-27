<?php

namespace Wscn\Controllers;

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
        $username = $this->request->get('username');
        $email = $this->request->get('email');

        if ($username) {
            $userinfo = Models\Login::findFirst(array("username = '$username'"));
        } elseif ($email) {
            $userinfo = Models\Login::findFirst(array("email = '$email'"));
        } else {
            $userinfo = array();
        }
        $this->view->disable();
        if ($userinfo) {
            $this->response->setStatusCode('409', 'User Already Exists');
        }

        return $this->response->setJsonContent(array(
            'exist' => $userinfo ? true : false,
            'id' => $userinfo ? $userinfo->id : 0,
            'status' => $userinfo ? $userinfo->status : null,
        ));
    }

}
