<?php

namespace Eva\EvaLivenews\Controllers\Admin;

use Eva\EvaLivenews\Models;
use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;
use Eva\EvaEngine\Exception;

class ProcessController extends ControllerBase implements JsonControllerInterface
{
    public function newsAction()
    {
        if (!$this->request->isDelete()) {
            return $this->showErrorMessageAsJson(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $id = $this->dispatcher->getParam('id');
        $news =  new Models\NewsManager();
        try {
            $news->removeNews($id);
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $news->getMessages());
        }

        return $this->response->setJsonContent($news);
    }
}
