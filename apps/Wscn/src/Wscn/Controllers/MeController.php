<?php

namespace Wscn\Controllers;

use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;

use Eva\EvaUser\Models\Login;

class MeController extends ControllerBase implements JsonControllerInterface
{
    public function indexAction()
    {
        return $this->response->setJsonContent(Login::getCurrentUser());
    }

}
