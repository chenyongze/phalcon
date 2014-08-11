<?php

namespace Wscn\Controllers;

use Eva\EvaUser\Models\Login;
use Eva\EvaBlog\Entities\Favors;
//use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;
use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;

//class StarsController extends ControllerBase implements SessionAuthorityControllerInterface, JsonControllerInterface
class StarsController extends ControllerBase implements JsonControllerInterface
{
    public function getAction()
    {
        $user = Login::getCurrentUser();
        $userId = $user['id'];
        $postId = $this->dispatcher->getParam('id', 'int');
        if($userId < 1 || $postId < 1) {
            return;
        }
        $star = Favors::findFirst("userId = $userId AND postId = $postId");
        if($star) {
            return $this->response->setJsonContent($star);
        } else {
            return $this->showErrorMessageAsJson(404, 'Request star not found');
        }
    }

    public function putAction()
    {
        $user = Login::getCurrentUser();
        $userId = $user['id'];
        $postId = $this->dispatcher->getParam('id', 'int');
        if($userId < 1 || $postId < 1) {
            return;
        }
        $star = Favors::findFirst("userId = $userId AND postId = $postId");
        if($star) {
            return $this->response->setJsonContent($star);
        }
        $star = new Favors();
        $star->userId = $userId;
        $star->postId = $postId;
        $star->createdAt = time();
        $star->save();
        return $this->response->setJsonContent($star);
    }

    public function deleteAction()
    {
        $user = Login::getCurrentUser();
        $userId = $user['id'];
        $postId = $this->dispatcher->getParam('id', 'int');
        if($userId < 1 || $postId < 1) {
            return;
        }
        $star = Favors::findFirst("userId = $userId AND postId = $postId");
        if($star) {
            $star->delete();
        } else {
            $star = new Favors();
            $star->userId = $userId;
            $star->postId = $postId;
        }
        return $this->response->setJsonContent($star);
    }

}
