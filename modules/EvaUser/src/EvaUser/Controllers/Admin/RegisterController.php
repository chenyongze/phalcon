<?php

namespace Eva\EvaUser\Controllers\Admin;

use Eva\EvaUser\Models;
use Eva\EvaUser\Forms;

class RegisterController extends ControllerBase
{
    public function indexAction()
    {
        if (!$this->request->isPost()) {
            return;
        }

        $form = new Forms\RegisterForm();
        if ($form->isValid($this->request->getPost()) === false) {
            $this->showInvalidMessages($form);

            return $this->response->redirect($this->getDI()->getConfig()->user->registerFailedRedirectUri);
        }
        $user = new Models\Register();
        $user->assign(array(
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ));
        try {
            $user->register();
        } catch (\Exception $e) {
            $this->showException($e, $user->getMessages());

            return $this->response->redirect($this->getDI()->getConfig()->user->registerFailedRedirectUri);
        }
        $this->flashSession->success('SUCCESS_USER_REGISTERED_ACTIVE_MAIL_SENT');

        return $this->response->redirect($this->getDI()->getConfig()->user->registerFailedRedirectUri);
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
