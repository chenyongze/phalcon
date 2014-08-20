<?php

namespace Eva\EvaSundries\Controllers\Admin;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-18 15:39
// +----------------------------------------------------------------------
// + BlockController.php
// +----------------------------------------------------------------------


use Eva\EvaEngine\Paginator;
use Eva\EvaSundries\Forms\BlockForm;
use Eva\EvaSundries\Models\Block;
use Eva\EvaEngine\Exception;

class BlockController extends AdminControllerBase
{
    public function indexAction()
    {
        $block = new Block();
        $pager = $block->findBlocks($this->dispatcher->getParams());
        $this->view->setVar('pager', $pager);
    }

    public function visibilityAction()
    {

        if (!$this->request->isPut()) {
            return $this->showErrorMessageAsJson(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $id = $this->dispatcher->getParam('id');

        $block = Block::findFirst($id);
        try {
            $block->visibility = $this->request->getPut('visibility');
            $block->save();
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $block->getMessages());
        }

        return $this->response->setJsonContent($block);
    }

    public function createAction()
    {
        $form = new BlockForm();
        $block = new Block();
        $form->setModel($block);
        $this->view->setVar('item', $block);
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            return false;
        }
        $data = $this->request->getPost();
        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessages($form);
        }
        try {
            if (!$form->save()) {
                return $this->showErrorMessage(422, $form->getModel()->getMessages()[0]);
            }
        } catch (\PDOException $pdoException) {
            if ($pdoException->getCode() == '23000') {
                $this->flashSession->error('BLOCK_NAME_USED_ALREADY');
                return;
            } else {
                return $this->showException($pdoException, $block->getMessages());
            }
        } catch (\Exception $e) {
            return $this->showException($e, $block->getMessages());
        }
        $this->flashSession->success('SUCCESS_BLOCK_CREATED');

        return $this->redirectHandler('/admin/block/edit/' . $form->getModel()->id);
    }

    public function editAction()
    {
        $this->view->changeRender('admin/block/create');
        $block = Block::findFirst($this->dispatcher->getParam('id'));
        if (!$block) {
            throw new Exception\ResourceNotFoundException('ERR_BLOCK_NOT_FOUND');
        }
        $form = new BlockForm();
        $form->setModel($block);
        $this->view->setVar('item', $block);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return false;
        }
        $data = $this->request->getPost();

        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessages($form);
        }

        try {
            $form->save();
        } catch (\Exception $e) {
            return $this->showException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_BLOCK_UPDATED');

        return $this->redirectHandler('/admin/block/edit/' . $block->id);
    }
}