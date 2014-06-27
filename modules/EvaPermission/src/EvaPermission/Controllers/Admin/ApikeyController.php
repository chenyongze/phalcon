<?php

namespace Eva\EvaPermission\Controllers\Admin;

use Eva\EvaPermission\Entities;
use Eva\EvaPermission\Forms;
use Eva\EvaEngine\Exception;

class ApikeyController extends ControllerBase
{
    /**
    * Index action
    */
    public function indexAction()
    {
        $query = array(
            'limit' => 1000,
            'page' => $this->request->getQuery('page', 'int', 1),
        );
        $itemQuery = $this->getDI()->getModelsManager()->createBuilder()
        ->from('Eva\EvaPermission\Entities\Apikeys');

        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $itemQuery,
            "limit"=> $query['limit'],
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);
    }

    public function createAction()
    {
        $form = new Forms\ApikeyForm();
        $apikey = new Entities\Apikeys();
        $form->setModel($apikey);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return false;
        }

        $form->bind($this->request->getPost(), $role);
        if (!$form->isValid()) {
            return $this->displayInvalidMessages($form);
        }
        $apikey = $form->getEntity();
        try {
            if(!$apikey->save()) {
                return $this->displayModelMessages($role);
            }
        } catch (\Exception $e) {
            return $this->displayException($e, $role->getMessages());
        }
        $this->flashSession->success('SUCCESS_APIKEY_CREATED');

        return $this->redirectHandler('/admin/permission/apikey/edit/' . $apikey->id);
    }

    public function editAction()
    {
        $this->view->changeRender('admin/role/create');

        $form = new Forms\RoleForm();
        $role = Entities\Roles::findFirst($this->dispatcher->getParam('id'));
        $form->setModel($role ? $role : new Entities\Roles());
        $this->view->setVar('form', $form);
        $this->view->setVar('item', $role);
        if (!$this->request->isPost()) {
            return false;
        }

        $form->bind($this->request->getPost(), $role);
        if (!$form->isValid()) {
            return $this->displayInvalidMessages($form);
        }
        $role = $form->getEntity();
        $role->assign($this->request->getPost());
        try {
            $role->save();
        } catch (\Exception $e) {
            return $this->displayException($e, $role->getMessages());
        }
        $this->flashSession->success('SUCCESS_ROLE_UPDATED');

        return $this->redirectHandler('/admin/permission/role/edit/' . $role->id);
    }

    public function deleteAction()
    {
        $this->response->setContentType('application/json', 'utf-8');
        if (!$this->request->isDelete()) {
            $this->response->setStatusCode('405', 'Method Not Allowed');
            return $this->response->setJsonContent(array(
                'errors' => array(
                    array(
                        'code' => 405,
                        'message' => 'ERR_POST_REQUEST_METHOD_NOT_ALLOW'
                    )
                ),
            ));
        }

        $id = $this->dispatcher->getParam('id');
        $role = Entities\Roles::findFirst($id);
        try {
            $role->delete();
        } catch (\Exception $e) {
            return $this->displayExceptionForJson($e, $role->getMessages());
        }
        return $this->response->setJsonContent($role);
    }
}
