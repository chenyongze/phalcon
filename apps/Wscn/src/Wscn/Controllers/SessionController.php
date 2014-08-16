<?php

namespace Wscn\Controllers;

use Eva\EvaUser\Models;
use Eva\EvaUser\Forms;

class SessionController extends ControllerBase
{
    public function verifyAction()
    {
        $code = $this->dispatcher->getParam('code');
        $username = $this->dispatcher->getParam('username');
        $user = new Models\Register();

        try {
            $user->verifyNewUser($username, $code);
        } catch (\Exception $e) {
            $this->showException($e, $user->getMessages());
            return $this->redirectHandler($this->getDI()->getConfig()->user->activeFailedRedirectUri, 'error');
        }
        $this->flashSession->success('SUCCESS_USER_ACTIVED');
        return $this->redirectHandler($this->getDI()->getConfig()->user->activeSuccessRedirectUri);
    }

    public function forgotAction()
    {
        $this->dispatcher->forward(array(
            'namespace' => 'Eva\EvaUser\Controllers',
            'controller' => 'session',
            'action' => 'forgot',
        ));
    }

    public function resetAction()
    {
        $this->dispatcher->forward(array(
            'namespace' => 'Eva\EvaUser\Controllers',
            'controller' => 'session',
            'action' => 'reset',
        ));
    }

    public function changemailAction()
    {
        $code = $this->dispatcher->getParam('code');
        $username = $this->dispatcher->getParam('username');
        $email = $this->dispatcher->getParam('email');
        $user = new Models\User();

        try {
            $user->changeEmail($username, $email, $code);
            $this->flash->success('用户邮箱已更改');
        } catch (\Exception $e) {
            $this->showException($e, $user->getMessages());
        }
    }
}
