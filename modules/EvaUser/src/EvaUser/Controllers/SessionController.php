<?php

namespace Eva\EvaUser\Controllers;

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

            return $this->response->redirect($this->getDI()->getConfig()->user->activeFailedRedirectUri);
        }
        $this->flashSession->success('SUCCESS_USER_ACTIVED');

        return $this->response->redirect($this->getDI()->getConfig()->user->activeSuccessRedirectUri);
    }

    public function forgotAction()
    {
        if (!$this->request->isPost()) {
            return;
        }

        $email = $this->request->getPost('email');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->redirect($this->getDI()->getConfig()->user->resetFailedRedirectUri);
        }

        $user = new Models\ResetPassword();
        $user->assign(array(
            'email' => $email,
        ));
        try {
            $user->requestResetPassword();
            $this->flashSession->success('SUCCESS_USER_RESET_MAIL_SENT');
        } catch (\Exception $e) {
            $this->showException($e, $user->getMessages());

            return $this->response->redirect($this->getDI()->getConfig()->user->resetFailedRedirectUri);
        }

        return $this->response->redirect($this->getDI()->getConfig()->user->resetSuccessRedirectUri);
    }

    public function resetAction()
    {
        $this->view->changeRender('admin/session/reset');
        $code = $this->dispatcher->getParam('code');
        $username = $this->dispatcher->getParam('username');
        $user = new Models\ResetPassword();
        try {
            $user->verifyPasswordReset($username, $code);
        } catch (\Exception $e) {
            $this->showException($e, $user->getMessages());

            return $this->response->redirect($this->getDI()->getConfig()->user->resetFailedRedirectUri);
        }

        if (!$this->request->isPost()) {
            return;
        }

        $form = new Forms\ResetPasswordForm();
        if ($form->isValid($this->request->getPost()) === false) {
            $this->showInvalidMessages($form);

            return $this->response->redirect($this->getDI()->getConfig()->user->resetFailedRedirectUri);
        }

        $user->assign(array(
            'username' => $username,
            'password' => $this->request->getPost('password'),
        ));
        try {
            $user->resetPassword();
            $this->flashSession->success('SUCCESS_USER_PASSWORD_RESET');
        } catch (\Exception $e) {
            $this->showException($e, $user->getMessages());

            return $this->response->redirect($this->getDI()->getConfig()->user->resetFailedRedirectUri);
        }

        return $this->response->redirect($this->getDI()->getConfig()->user->resetSuccessRedirectUri);
    }

    public function testAction()
    {
        $user = new Models\Login();
        $authIdentity = $user->getAuthIdentity();
        if (!$authIdentity && ($tokenString = $this->cookies->get('realm')->getValue())) {
            if ($user->loginWithCookie($tokenString)) {
            } else {
                $this->cookies->delete('realm');
            }
        }
    }

}
