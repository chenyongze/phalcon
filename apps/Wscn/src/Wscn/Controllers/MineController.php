<?php

namespace Wscn\Controllers;

use Eva\EvaUser\Models\Login;
use Eva\EvaUser\Models\User;
use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;

class MineController extends ControllerBase implements SessionAuthorityControllerInterface
{
    public function dashboardAction()
    {
        $user = Login::getCurrentUser();
    }

    public function profileAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $form = new \Eva\EvaUser\Forms\UserForm();
        $form->setModel($user);
        $form->addForm('profile', 'Eva\EvaUser\Forms\ProfileForm');
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);
    }

    public function passwordAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $form = new \Eva\EvaUser\Forms\ChangePasswordForm();
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);
    
    }

    public function emailAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $form = new \Eva\EvaUser\Forms\ChangeEmailForm();
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);
    }
}
