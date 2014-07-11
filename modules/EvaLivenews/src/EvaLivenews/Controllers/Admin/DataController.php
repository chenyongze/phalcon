<?php

namespace Eva\EvaLivenews\Controllers\Admin;

use Eva\EvaLivenews\Models;
use Eva\EvaLivenews\Forms;
use Eva\EvaEngine\Exception;
use Phalcon\Mvc\View;

class DataController extends ControllerBase
{
    public function createAction()
    {
        $response = $this->dispatcher->forward(array(
            "controller" => 'Admin\News',
            "action" => "create",
        ));
        $this->view->changeRender('admin/data/create');
    }

    public function editAction()
    {
        $this->view->changeRender('admin/data/create');
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

        if($this->request->isAjax()) {
            if (!$form->isFullValid($data)) {
                return $this->displayJsonInvalidMessages($form);
            }
            try {
                $form->save('updateNews');
            } catch (\Exception $e) {
                return $this->displayExceptionForJson($e, $form->getModel()->getMessages());
            }
            return $this->displayJsonResponse($form->getModel()->dump(Models\NewsManager::$defaultDump));
        } else {
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
}
