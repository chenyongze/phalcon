<?php

namespace VnetWap\Controllers;

use Eva\EvaEngine\Exception;
use Phalcon\Http\Client\Request;

class LivenewsController extends ControllerBase
{
    public function indexAction()
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
}
