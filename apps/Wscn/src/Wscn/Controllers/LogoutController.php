<?php

namespace Wscn\Controllers;

class LogoutController extends ControllerBase
{
    public function indexAction()
    {
        $this->cookies->delete('realm');
        $this->getDI()->getSession()->remove('auth-identity');
        return $this->response->redirect('/');
    }

}
