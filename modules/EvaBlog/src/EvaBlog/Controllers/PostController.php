<?php

namespace Eva\EvaBlog\Controllers;

use Eva\EvaBlog\Models;
use Eva\EvaBlog\Models\Post;

class PostController extends \Phalcon\Mvc\Controller
{
    public function listAction()
    {
        $limit = $this->dispatcher->getParam('limit');
        $limit = $limit ? $limit : 25;
        /** @noinspection PhpDuplicateArrayKeysInspection */
        $query = array(
            'q' => $this->dispatcher->getParam('q'),
            'status' => $this->dispatcher->getParam('status'),
            'uid' => $this->dispatcher->getParam('uid'),
            'cid' => $this->dispatcher->getParam('cid'),
            'tid' => $this->dispatcher->getParam('tid'),
            'has_image' => $this->dispatcher->getParam('has_image', 'int'),
            'min_created_at' => $this->dispatcher->getParam('min_created_at', 'int'),
            'username' => $this->dispatcher->getParam('username'),
            'order' => $this->dispatcher->getParam('order'),
            'limit' => $limit,
            'page' => $this->dispatcher->getParam('page'),
        );
        $post = new Models\Post();
        $posts = $post->findPosts($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $posts,
            "limit"=> $query['limit'],
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        return $pager;
    }
}
