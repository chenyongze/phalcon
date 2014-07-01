<?php

namespace Eva\EvaLivenews\Controllers\Admin;

use Eva\EvaLivenews\Models;
use Eva\EvaLivenews\Models\Livenews;
use Eva\EvaLivenews\Forms;
use Eva\EvaEngine\Exception;

class NewsController extends ControllerBase
{
    /**
    * Index action
    */
    public function indexAction()
    {
        $limit = $this->request->getQuery('limit', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 10 ? 10 : $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'status' => $this->request->getQuery('status', 'string'),
            'uid' => $this->request->getQuery('uid', 'int'),
            'cid' => $this->request->getQuery('cid', 'int'),
            'username' => $this->request->getQuery('username', 'string'),
            'order' => $order,
            'limit' => $limit,
            'page' => $this->request->getQuery('page', 'int', 1),
        );

        $form = new Forms\FilterForm();
        $form->setValues($this->request->getQuery());
        $this->view->setVar('form', $form);

        $news = new Models\News();
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
        $news = new Models\News();
        $form->setModel($news);
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
        $this->flashSession->success('SUCCESS_POST_CREATED');

        return $this->redirectHandler('/admin/livenews/edit/' . $form->getModel()->id);
    }

    public function editAction()
    {
        $this->view->changeRender('admin/livenews/create');
        $livenews = Models\Livenews::findFirst($this->dispatcher->getParam('id'));
        if (!$livenews) {
            throw new Exception\ResourceNotFoundException('ERR_BLOG_POST_NOT_FOUND');
        }

        $form = new Forms\LivenewsForm();
        $form->setModel($livenews);
        $form->addForm('text', 'Eva\EvaLivenews\Forms\TextForm');
        $this->view->setVar('form', $form);
        $this->view->setVar('item', $livenews);

        if (!$this->request->isLivenews()) {
            return false;
        }
        $data = $this->request->getLivenews();

        if (!$form->isFullValid($data)) {
            return $this->displayInvalidMessages($form);
        }

        try {
            $form->save('updateLivenews');
        } catch (\Exception $e) {
            return $this->displayException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_POST_UPDATED');

        return $this->redirectHandler('/admin/livenews/edit/' . $livenews->id);
    }
}
