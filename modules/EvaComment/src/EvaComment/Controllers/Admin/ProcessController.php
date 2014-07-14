<?php

namespace Eva\EvaComment\Controllers\Admin;

use Eva\EvaComment\Models;
use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;
use Eva\EvaEngine\Exception;

class ProcessController extends ControllerBase implements JsonControllerInterface
{
    public function statusAction()
    {
        if (!$this->request->isPut()) {
            return $this->showErrorMessageAsJson(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $id = $this->dispatcher->getParam('id');
        $status = $this->request->getPut('status');

        $commentManager =  new Models\CommentManager();

        $comment = $commentManager->findCommentById($id);

        try {
            $commentManager->updateCommentStatus($comment,$status);
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $comment->getMessages());
        }

        return $this->response->setJsonContent($comment);
    }

    public function batchAction()
    {
        if (!$this->request->isPut()) {
            return $this->showErrorMessageAsJson(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $idArray = $this->request->getPut('id');
        if (!is_array($idArray) || count($idArray) < 1) {
            return $this->showErrorMessageAsJson(401, 'ERR_REQUEST_PARAMS_INCORRECT');
        }

        $status = $this->request->getPut('status');
        $comments = array();

        $commentManager =  new Models\CommentManager();


        try {
            foreach ($idArray as $id) {

                $comment = $commentManager->findCommentById($id);

                if ($comment) {
                    $commentManager->updateCommentStatus($comment,$status);

                    $comments[] = $comment;
                }
            }
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $comment->getMessages());
        }

        return $this->response->setJsonContent($comments);
    }
}
