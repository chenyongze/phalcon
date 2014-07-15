<?php

namespace Eva\EvaLivenews\Controllers\Admin;

use Eva\EvaLivenews\Models;
use Eva\EvaLivenews\Forms;
use Eva\EvaEngine\Exception;
use Phalcon\Mvc\View;

class NewsController extends ControllerBase
{
    public function embedAction()
    {
        $this->indexAction();
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
    * Index action
    */
    public function indexAction()
    {
        $limit = $this->request->getQuery('per_page', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 10 ? 10 : $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'status' => $this->request->getQuery('status', 'string'),
            'uid' => $this->request->getQuery('uid', 'int'),
            'cid' => $this->request->getQuery('cid', 'int'),
            'username' => $this->request->getQuery('username', 'string'),
            'codeType' => $this->request->getQuery('code_type', 'string'),
            'order' => $order,
            'limit' => $limit,
            'page' => $this->request->getQuery('page', 'int', 1),
        );

        $form = new Forms\FilterForm();
        $form->setValues($this->request->getQuery());
        $this->view->setVar('form', $form);

        $news = new Models\NewsManager();
        $newsSet = $news->findNews($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $newsSet,
            "limit"=> $limit,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);
    }

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

        if($this->request->isAjax()) {
            if (!$form->isFullValid($data)) {
                return $this->showInvalidMessagesAsJson($form);
            }
            try {
                $form->save('createNews');
            } catch (\Exception $e) {
                return $this->showExceptionAsJson($e, $form->getModel()->getMessages());
            }
            return $this->showResponseAsJson($form->getModel()->dump(Models\NewsManager::$defaultDump));
        } else {
            if (!$form->isFullValid($data)) {
                return $this->showInvalidMessages($form);
            }
            try {
                $form->save('createNews');
            } catch (\Exception $e) {
                return $this->showException($e, $form->getModel()->getMessages());
            }
            $this->flashSession->success('SUCCESS_NEWS_CREATED');
            return $this->redirectHandler('/admin/livenews/news/edit/' . $form->getModel()->id);
        }
    }

    public function editAction()
    {
        $this->view->changeRender('admin/news/create');
        $news = Models\NewsManager::findFirst($this->dispatcher->getParam('id'));
        if (!$news) {
            throw new Exception\ResourceNotFoundException('ERR_LIVENEWS_NEWS_NOT_FOUND');
        }

        if($news->codeType == 'json') {
            return $this->redirectHandler('/admin/livenews/data/edit/' . $news->id);
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
                return $this->showInvalidMessagesAsJson($form);
            }
            try {
                $form->save('updateNews');
            } catch (\Exception $e) {
                return $this->showExceptionAsJson($e, $form->getModel()->getMessages());
            }
            return $this->showResponseAsJson($form->getModel()->dump(Models\NewsManager::$defaultDump));
        } else {
            if (!$form->isFullValid($data)) {
                return $this->showInvalidMessages($form);
            }
            try {
                $form->save('updateNews');
            } catch (\Exception $e) {
                return $this->showException($e, $form->getModel()->getMessages());
            }
            $this->flashSession->success('SUCCESS_NEWS_UPDATED');
            return $this->redirectHandler('/admin/livenews/news/edit/' . $news->id);
        }
    }
}
