<?php

namespace WscnWap\Controllers;

use Eva\EvaEngine\Exception;
use Phalcon\Http\Client\Request;


class IndexController extends ControllerBase
{
    public function beforeExecuteRoute()
    {
        $cacheKey = 'node-' . md5($this->request->getURI());
        $this->view->cache(array(
            'lifetime' => 60,
            'key' => $cacheKey,
        ));
        if($this->view->getCache()->exists($cacheKey)) {
            return false;
        }
    }

    public function indexAction()
    {
        $page = $this->request->getQuery('page', 'int', '0');
        $provider  = Request::getProvider();
        $provider->setBaseUri('http://api.wallstreetcn.com/apiv1/');
        $response = $provider->get('news-list.json', array(
            'page' => $page,
        ));
        //$response->header->statusCode;
        $posts = json_decode($response->body);
        $this->view->setVar('posts', $posts);
        $this->view->setVar('page', $page);
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
