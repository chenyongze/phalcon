<?php

namespace Eva\EvaLivenews\Controllers\Admin;

use Eva\EvaLivenews\Models;
use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;
use Eva\EvaEngine\Exception;

/**
* @resourceName("Livenews Managment Assists")
* @resourceDescription("Livenews Managment Assists (Ajax json format)")
*/
class ProcessController extends ControllerBase implements JsonControllerInterface
{

    /**
    * @operationName("Remove Livenews")
    * @operationDescription("Remove Livenews")
    */
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

    /**
    * @operationName("Change livenews status")
    * @operationDescription("Change livenews status")
    */
    public function statusAction()
    {
        if (!$this->request->isPut()) {
            return $this->showErrorMessageAsJson(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $id = $this->dispatcher->getParam('id');
        $post =  Models\NewsManager::findFirst($id);
        try {
            $post->status = $this->request->getPut('status');
            $post->updatedAt = time();
            $post->save();
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $post->getMessages());
        }

        return $this->response->setJsonContent($post);
    }
}
