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
        $query = array(
            'status' => 'published',
            'cid' => $this->request->getQuery('cid', 'string'),
            'type' => $this->request->getQuery('type', 'string'),
            'importance' => $this->request->getQuery('importance', 'string'),
            'order' => '-updated_at',
            'page' => $this->request->getQuery('page', 'int', 1),
        );

        $form = new Forms\FilterForm();
        $form->setValues($this->request->getQuery());
        $this->view->setVar('form', $form);

        $news = new Models\NewsManager();
        $newsSet = $news->findNews($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $newsSet,
            "limit"=> 40,
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
