<?php

namespace WscnMobile\Controllers;

use Eva\EvaEngine\Exception;
use Phalcon\Http\Client\Request;
use Eva\EvaBlog\Models\Post;

class IndexController extends ControllerBase
{
    public function beforeExecuteRoute()
    {
    }

    public function indexAction()
    {
        $limit = $this->request->getQuery('limit', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 10 ? 10 : $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'status' => $this->request->getQuery('status', 'string', 'published'),
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

        return $paginator;

    }

    public function livenewsAction()
    {
        $page = $this->request->getQuery('page', 'int', '0');
        $provider  = Request::getProvider();
        $provider->setBaseUri('http://api.wallstreetcn.com/apiv1/');
        $response = $provider->get('livenews-list-v2.json', array(
            'page' => $page,
        ));
        //$response->header->statusCode;
        $posts = json_decode($response->body);
        $this->view->setVar('posts', $posts);
        $this->view->setVar('page', $page);
    }

    public function nodeAction()
    {
        $id = $this->dispatcher->getParam('id');
        $provider  = Request::getProvider();
        $provider->setBaseUri('http://api.wallstreetcn.com/apiv1/');
        $response = $provider->get("node/$id.json");
        //$response->header->statusCode;
        $post = json_decode($response->body);
        $this->view->setVar('post', $post);
    }
}
