<?php

namespace Wscn\Controllers;

use Eva\EvaUser\Models\Login;
use Eva\EvaBlog\Entities\Stars;
use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;
use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;

/**
* @resourceName("文章收藏")
* @resourceDescription("文章收藏相关动作")
*/
class StarsController extends ControllerBase implements SessionAuthorityControllerInterface, JsonControllerInterface
{

    /**
    * @operationName("获得收藏")
    * @operationDescription("获得收藏信息")
    */
    public function getAction()
    {
        $user = Login::getCurrentUser();
        $userId = $user['id'];
        $postId = $this->dispatcher->getParam('id', 'int');
        if($userId < 1 || $postId < 1) {
            return;
        }
        $star = Stars::findFirst("userId = $userId AND postId = $postId");
        if($star) {
            return $this->response->setJsonContent($star);
        } else {
            return $this->showErrorMessageAsJson(404, 'Request star not found');
        }
    }

    /**
    * @operationName("收藏文章")
    * @operationDescription("收藏文章")
    */
    public function putAction()
    {
        $user = Login::getCurrentUser();
        $userId = $user['id'];
        $postId = $this->dispatcher->getParam('id', 'int');
        if($userId < 1 || $postId < 1) {
            return;
        }
        $star = Stars::findFirst("userId = $userId AND postId = $postId");
        if($star) {
            return $this->response->setJsonContent($star);
        }
        $star = new Stars();
        $star->userId = $userId;
        $star->postId = $postId;
        $star->createdAt = time();
        $star->save();
        return $this->response->setJsonContent($star);
    }

    /**
    * @operationName("取消收藏文章")
    * @operationDescription("取消收藏文章")
    */
    public function deleteAction()
    {
        $user = Login::getCurrentUser();
        $userId = $user['id'];
        $postId = $this->dispatcher->getParam('id', 'int');
        if($userId < 1 || $postId < 1) {
            return;
        }
        $star = Stars::findFirst("userId = $userId AND postId = $postId");
        if($star) {
            $star->delete();
        } else {
            $star = new Stars();
            $star->userId = $userId;
            $star->postId = $postId;
        }
        return $this->response->setJsonContent($star);
    }

}
