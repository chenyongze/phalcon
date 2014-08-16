<?php

namespace Wscn\Controllers;

use Eva\EvaBlog\Models\Post;
use Eva\EvaBlog\Models\Category;
use Eva\EvaUser\Models\UserManager;
use Eva\EvaEngine\Exception;

class NewsController extends ControllerBase
{
    public function indexAction()
    {
        $limit = $this->request->getQuery('limit', 'int', 25);
        $limit = $limit > 100 ?: $limit;
        $limit = $limit < 10 ?: $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'status' => 'published',
            'uid' => $this->request->getQuery('uid', 'int'),
            'cid' => $this->request->getQuery('cid', 'int'),
            'username' => $this->request->getQuery('username', 'string'),
            'order' => $order,
            'limit' => $limit,
            'page' => $this->request->getQuery('page', 'int', 1),
        );

        if ($query['cid']) {
            $this->view->setVar('category', Category::findFirst($query['cid']));
        }

        if ($query['uid']) {
            $this->view->setVar('author', UserManager::findFirst($query['uid']));
        }

        $post = new Post();
        $posts = $post->findPosts($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $posts,
            "limit"=> $limit,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);
        $this->view->setVar('query', $query);
    }

    public function nodeAction()
    {
    }
}
