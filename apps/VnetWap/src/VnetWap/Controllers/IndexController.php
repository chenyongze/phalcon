<?php

namespace VnetWap\Controllers;

use Eva\EvaEngine\Exception;
use Phalcon\Http\Client\Request;


class IndexController extends ControllerBase
{

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
        /*获取头条*/
        $provider_top  = Request::getProvider();
        $provider_top->setBaseUri('http://api.wallstreetcn.com/apiv1/');
        $response_top = $provider_top->get('topnews-list.json');
        $posts_top = json_decode($response_top->body);
        $this->view->setVar('posts_top', $posts_top);
        /*获取头条*/
    }

    public function liveAction()
    {
    
    }

    public function recommendAction()
    {
        $page = $this->request->getQuery('page', 'int', '0');
        $provider  = Request::getProvider();
        $provider->setBaseUri('http://api.wallstreetcn.com/apiv1/');
        $response = $provider->get('news-list.json', array(
            'spid' => 3119,
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
