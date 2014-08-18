<?php

namespace WscnApiVer1\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->response->redirect($this->getDI()->get('config')->apiUri . '/explorer/index_v1.html');
    }

    public function resourcesAction()
    {
        $swagger = new \Swagger\Swagger(__DIR__);
        $content = json_encode($swagger->getResourceList());
        $this->response->setContentType('application/json', 'utf-8');

        return $this->response->setContent($content);
    }

    public function resourceAction()
    {
        $swagger = new \Swagger\Swagger(__DIR__);
        $resource = $swagger->getResource('/' . $this->dispatcher->getParam('id'));

        return $this->response->setJsonContent($resource);
    }
}
