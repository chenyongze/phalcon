<?php

namespace Wscn\Controllers;

use Eva\EvaUser\Models\Login;

class LogoutController extends ControllerBase
{
    public function indexAction()
    {
        $this->cookies->delete(Login::LOGIN_COOKIE_KEY);
        $this->cookies->delete(Login::LOGIN_COOKIE_REMEMBER_KEY);
        Login::getAuthStorage()->remove(Login::AUTH_KEY_LOGIN);
        Login::getAuthStorage()->remove(Login::AUTH_KEY_TOKEN);
        Login::getAuthStorage()->remove(Login::AUTH_KEY_ROLES);
        return $this->response->redirect('/');
    }
}
