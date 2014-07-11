<?php

namespace Eva\EvaUser\Controllers\Admin;

use Eva\EvaUser\Forms;
use Eva\EvaUser\Models;
use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;

/**
* @resourceName("Admin User Managment")
* @resourceDescription("用户管理")
*/
class UserController extends AdminControllerBase implements SessionAuthorityControllerInterface
{

    /**
    * @operationName("User List")
    * @operationDescription("用户列表页")
    */
     public function indexAction()
     {
        $limit = $this->request->getQuery('per_page', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 10 ? 10 : $limit;
        $query = array(
            //'q' => $this->request->getQuery('q', 'string'),
            'status' => $this->request->getQuery('status', 'string'),
            'uid' => $this->request->getQuery('uid', 'int'),
            'cid' => $this->request->getQuery('cid', 'int'),
            'username' => $this->request->getQuery('username', 'string'),
            'order' => $this->request->getQuery('order', 'string'),
            'limit' => $limit,
            'page' => $this->request->getQuery('page', 'int', 1),
        );

        $form = new Forms\FilterForm();
        $form->setValues($this->request->getQuery());
        $this->view->setVar('form', $form);

        $user = new Models\User();
        $users = $user->findUsers($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $users,
            "limit"=> $limit,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);

    }

    /**
    * @operationName("Create User")
    * @operationDescription("创建用户")
    */
    public function createAction()
    {
        $user = new Models\User();
        $form = new \Eva\EvaUser\Forms\UserForm();
        $form->setModel($user);
        $form->addForm('profile', 'Eva\EvaUser\Forms\ProfileForm');
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return false;
        }

        $data = $this->request->getPost();
        if (!$form->isFullValid($data)) {
            return $this->displayInvalidMessages($form);
        }

        try {
            $form->save();
        } catch (\Exception $e) {
            return $this->displayException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_USER_CREATED');

        return $this->redirectHandler('/admin/user/edit/' . $form->getModel()->id);
    }

    /**
    * @operationName("Edit User")
    * @operationDescription("编辑用户")
    */
    public function editAction()
    {
        $this->view->changeRender('admin/user/create');
        $user = Models\User::findFirst($this->dispatcher->getParam('id'));
        if (!$user) {

        }

        $form = new \Eva\EvaUser\Forms\UserForm();
        $form->setModel($user);
        $form->addForm('profile', 'Eva\EvaUser\Forms\ProfileForm');
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return false;
        }

        $data = $this->request->getPost();
        if (!$form->isFullValid($data)) {
            return $this->displayInvalidMessages($form);
        }

        try {
            $form->save();
        } catch (\Exception $e) {
            return $this->displayException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_USER_UPDATED');

        return $this->redirectHandler('/admin/user/edit/' . $user->id);

    }

}
