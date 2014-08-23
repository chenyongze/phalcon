<?php

namespace Wscn\Controllers\Admin;

use Wscn\Entities;
use Eva\EvaEngine\Exception;
use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;

/**
* @resourceName("App相关资源管理后台")
* @resourceDescription("App相关资源管理后台")
*/
class AppoptionController extends ControllerBase implements SessionAuthorityControllerInterface
{
    /**
    * @operationName("App资源列表")
    * @operationDescription("App资源列表")
    */
    public function indexAction()
    {
        $currentPage = $this->request->getQuery('page', 'int'); // GET
        $limit = $this->request->getQuery('limit', 'int');
        $order = $this->request->getQuery('order', 'int');
        $limit = $limit > 50 ? 50 : $limit;
        $limit = $limit < 10 ? 10 : $limit;

        $items = $this->modelsManager->createBuilder()
            ->from('Wscn\Entities\Appoptions')
            ->orderBy('id DESC');

        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $items,
            "limit"=> 500,
            "page" => $currentPage
        ));
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);
    }

    /**
    * @operationName("创建App资源")
    * @operationDescription("创建App资源")
    */
    public function createAction()
    {
        $form = new \Wscn\Forms\AppoptionForm();
        $appoption = new Entities\Appoptions();
        $form->setModel($appoption);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return false;
        }

        $form->bind($this->request->getPost(), $appoption);
        if (!$form->isValid()) {
            return $this->showInvalidMessages($form);
        }
        $appoption = $form->getEntity();
        try {
            if (!$appoption->save()) {
                throw new Exception\RuntimeException('Create appoption failed');
            }
            $this->flashSession->success('SUCCESS_APPOPTIONS_CREATED');
        } catch (\Exception $e) {
            return $this->showException($e, $appoption->getMessages());
        }
        return $this->redirectHandler('/admin/appoption/edit/' . $appoption->id);
    }

    /**
    * @operationName("编辑App资源")
    * @operationDescription("编辑App资源")
    */
    public function editAction()
    {
        $this->view->changeRender('admin/appoption/create');

        $form = new \Wscn\Forms\AppoptionForm();
        $appoption = Entities\Appoptions::findFirst($this->dispatcher->getParam('id'));
        $form->setModel($appoption ? $appoption : new Entities\Appoptions());
        $this->view->setVar('form', $form);
        $this->view->setVar('item', $appoption);
        if (!$this->request->isPost()) {
            return false;
        }

        $form->bind($this->request->getPost(), $appoption);
        if (!$form->isValid()) {
            return $this->showInvalidMessages($form);
        }
        $appoption = $form->getEntity();
        $appoption->assign($this->request->getPost());
        try {
            $appoption->save();
        } catch (\Exception $e) {
            return $this->showException($e, $appoption->getMessages());
        }
        $this->flashSession->success('SUCCESS_APPOPTIONS_UPDATED');

        return $this->redirectHandler('/admin/appoption/edit/' . $appoption->id);
    }

    /**
    * @operationName("删除App资源")
    * @operationDescription("删除App资源")
    */
    public function deleteAction()
    {
    }
}
