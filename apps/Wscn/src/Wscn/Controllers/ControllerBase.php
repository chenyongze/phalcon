<?php

namespace Wscn\Controllers;

class ControllerBase extends \Eva\EvaEngine\Mvc\Controller\ControllerBase
{
    public function initialize()
    {
        $cacheKey = md5($this->request->getURI());
        $this->view->cache(array(
            'lifetime' => 60,
            'key' => $cacheKey,
        ));
        $this->view->setModuleLayout('Wscn', '/views/layouts/default');
        $this->view->setModuleViewsDir('Wscn', '/views');
        $this->view->setModulePartialsDir('Wscn', '/views');
    }

}
