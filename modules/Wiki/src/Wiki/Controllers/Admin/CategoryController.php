<?php

namespace Eva\Wiki\Controllers\Admin;

use Eva\Wiki\Forms\CategoryForm;
use Eva\EvaEngine\Paginator;
use Eva\Wiki\Models;

class CategoryController extends AdminControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $currentPage = $this->request->getQuery('page', 'int'); // GET
        $limit = $this->request->getQuery('limit', 'int');
//        $order = $this->request->getQuery('order', 'int');


        $catModel = new Models\Category();
        $this->view->setVar('pager', $catModel->listCategories($limit, $currentPage));
    }

    public function treeAction()
    {

    }

    public function createAction()
    {
        $form = new CategoryForm();
        $category = new Models\Category();
        $form->setModel($category);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return false;
        }
        $data = $this->request->getPost();
        $form->bind($data, $category);
        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessages($form);
        }
        $category = $form->getEntity();

        try {
            $form->save('createCategory');
        } catch (\Exception $e) {
            return $this->showException($e, $category->getMessages());
        }
        $this->flashSession->success('SUCCESS_BLOG_CATEGORY_CREATED');

        return $this->redirectHandler('/admin/wiki/category/edit/' . $category->id);
    }

    public function editAction()
    {
        $this->view->changeRender('admin/category/create');

        $form = new CategoryForm();
        $category = Models\Category::findFirst($this->dispatcher->getParam('id'));
        $form->setModel($category ? $category : new Models\Category());
        $this->view->setVar('form', $form);
        $this->view->setVar('item', $category);
        if (!$this->request->isPost()) {
            return false;
        }
        $data = $this->request->getPost();
        $form->bind($data, $category);
        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessages($form);
        }
        $category = $form->getEntity();
        $category->assign($this->request->getPost());
        try {
            $form->save('updateCategory');
        } catch (\Exception $e) {
            return $this->showException($e, $category->getMessages());
        }
        $this->flashSession->success('SUCCESS_BLOG_CATEGORY_UPDATED');

        return $this->redirectHandler('/admin/wiki/category/edit/' . $category->id);
    }

    public function deleteAction()
    {
        if (!$this->request->isDelete()) {
            $this->response->setStatusCode('405', 'Method Not Allowed');
            $this->response->setContentType('application/json', 'utf-8');

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
        $category = Models\Category::findFirst($id);
        try {
            $category->delete();
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $category->getMessages());
        }

        $this->response->setContentType('application/json', 'utf-8');

        return $this->response->setJsonContent($category);
    }
}
