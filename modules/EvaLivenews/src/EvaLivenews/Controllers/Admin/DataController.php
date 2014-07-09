<?php

namespace Eva\EvaLivenews\Controllers\Admin;

use Eva\EvaLivenews\Models;
use Eva\EvaLivenews\Models\Livenews;
use Eva\EvaLivenews\Forms;
use Eva\EvaEngine\Exception;
use Phalcon\Mvc\View;

class DataController extends ControllerBase
{
    public function createAction()
    {
        $form = new Forms\NewsForm();
        $news = new Models\NewsManager();
        $form->setModel($news);
        $form->addForm('text', 'Eva\EvaLivenews\Forms\TextForm');
        $this->view->setVar('form', $form);
        $this->view->setVar('item', $news);

        if (!$this->request->isPost()) {
            return false;
        }

        $data = $this->request->getPost();
        if (!$form->isFullValid($data)) {
            return $this->displayInvalidMessages($form);
        }

        try {
            $form->save('createNews');
        } catch (\Exception $e) {
            return $this->displayException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_NEWS_CREATED');

        return $this->redirectHandler('/admin/livenews/news/edit/' . $form->getModel()->id);
    }

    public function editAction()
    {
        $this->view->changeRender('admin/news/create');
        $news = Models\NewsManager::findFirst($this->dispatcher->getParam('id'));
        if (!$news) {
            throw new Exception\ResourceNotFoundException('ERR_LIVENEWS_NEWS_NOT_FOUND');
        }

        $form = new Forms\NewsForm();
        $form->setModel($news);
        $form->addForm('text', 'Eva\EvaLivenews\Forms\TextForm');
        $this->view->setVar('form', $form);
        $this->view->setVar('item', $news);

        if (!$this->request->isPost()) {
            return false;
        }
        $data = $this->request->getPost();

        if (!$form->isFullValid($data)) {
            return $this->displayInvalidMessages($form);
        }

        try {
            $form->save('updateNews');
        } catch (\Exception $e) {
            return $this->displayException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_NEWS_UPDATED');

        return $this->redirectHandler('/admin/livenews/news/edit/' . $news->id);
    }
}
