<?php

namespace Wscn\Controllers;

use Eva\EvaBlog\Models\Post;
use Eva\EvaEngine\Exception;
use Eva\EvaLivenews\Models;
use Eva\EvaLivenews\Forms;

class LivenewsController extends ControllerBase
{
    public function indexAction()
    {
        $limit = $this->request->getQuery('per_page', 'int', 50);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 10 ? 10 : $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'status' => $this->request->getQuery('status', 'string'),
            'uid' => $this->request->getQuery('uid', 'int'),
            'cid' => $this->request->getQuery('cid', 'string'),
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

    public function nodeAction()
    {
    }
}
