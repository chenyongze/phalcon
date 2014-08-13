<?php

namespace Wscn\Controllers;

use Eva\EvaUser\Models\Login;
use Eva\EvaUser\Models\User;
use Eva\EvaUser\Forms;
use Wscn\Forms\UserForm;
use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;

class MineController extends ControllerBase implements SessionAuthorityControllerInterface
{
    public function dashboardAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $this->view->setVar('item', $user);
    }

    public function profileAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $form = new UserForm();
        $form->setModel($user);
        $form->addForm('profile', 'Eva\EvaUser\Forms\ProfileForm');
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            return;
        }

        $data = $this->request->getPost();
        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessages($form);
        }

        try {
            $form->save('changeProfile');
        } catch (\Exception $e) {
            return $this->showException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_USER_UPDATED');
        return $this->redirectHandler('/mine/profile');
    }

    public function passwordAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $form = new Forms\ChangePasswordForm();
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return;
        }

        if ($this->request->isAjax()) {
            if ($form->isValid($this->request->getPost()) === false) {
                return $this->showInvalidMessagesAsJson($form);
            }
            try {
                $user->changePassword($this->request->getPost('password'), $this->request->getPost('passwordNew'));
                return $this->showResponseAsJson(Login::getCurrentUser());
            } catch (\Exception $e) {
                return $this->showExceptionAsJson($e, $user->getMessages());
            }

        } else {
            if ($form->isValid($this->request->getPost()) === false) {
                $this->showInvalidMessages($form);
                return $this->redirectHandler('/mine/password');
            }

            try {
                $user->changePassword($this->request->getPost('password'), $this->request->getPost('passwordNew'));
                $this->flashSession->success('密码已更改');
                return $this->redirectHandler('/mine/password');
            } catch (\Exception $e) {
                $this->showException($e, $user->getMessages());
                return $this->redirectHandler('/mine/password');
            }
        }
    }

    public function emailAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $form = new \Eva\EvaUser\Forms\ChangeEmailForm();
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return;
        }

        if ($this->request->isAjax()) {
            try {
                $user->requestChangeEmail($this->request->getPost('email'));
                return $this->showResponseAsJson(Login::getCurrentUser());
            } catch (\Exception $e) {
                return $this->showExceptionAsJson($e, $user->getMessages());
            }

        } else {
            try {
                $user->requestChangeEmail($this->request->getPost('email'));
                $this->flashSession->success('新邮箱验证邮件已发送，请登录邮箱验证');
                return $this->redirectHandler('/mine/email');
            } catch (\Exception $e) {
                $this->showException($e, $user->getMessages());
                return $this->redirectHandler('/mine/email');
            }
        }
    }

    public function oauthAction()
    {
    }

    public function commentsAction()
    {
    
    }

    public function starsAction()
    {
    }
}
