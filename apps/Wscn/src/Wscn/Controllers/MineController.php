<?php

namespace Wscn\Controllers;

use Eva\EvaUser\Models\Login;
use Eva\EvaUser\Models\User;
use Eva\EvaBlog\Models\Star;
use Eva\EvaComment\Models\CommentManager as Comment;
use Eva\EvaOAuthClient\Models\OAuthManager;
use Eva\EvaUser\Forms;
use Wscn\Forms\UserForm;
use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;
use Eva\EvaEngine\Paginator;

/**
* @resourceName("用户中心")
* @resourceDescription("用户中心相关资源")
*/
class MineController extends ControllerBase implements SessionAuthorityControllerInterface
{
    /**
    * @operationName("用户中心首页")
    * @operationDescription("用户中心首页")
    */
    public function dashboardAction()
    {
        return $this->redirectHandler('/mine/stars');
        $this->dispatcher->forward(array(
            'action' => 'stars'
        ));
    }

    /**
    * @operationName("更新用户资料")
    * @operationDescription("更新用户资料")
    */
    public function profileAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $form = new UserForm();
        $form->setModel($user);
        $form->addForm('profile', 'Eva\EvaUser\Forms\ProfileForm');
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            return;
        }

        $data = $this->request->getPost();
        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessages($form);
        }

        try {
            $form->save('changeProfile');
        } catch (\Exception $e) {
            return $this->showException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_USER_UPDATED');
        return $this->redirectHandler('/mine/profile');
    }

    /**
    * @operationName("更改密码")
    * @operationDescription("更改密码")
    */
    public function passwordAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $form = new Forms\ChangePasswordForm();
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return;
        }

        if ($this->request->isAjax()) {
            if ($form->isValid($this->request->getPost()) === false) {
                return $this->showInvalidMessagesAsJson($form);
            }
            try {
                $user->changePassword($this->request->getPost('password'), $this->request->getPost('passwordNew'));
                return $this->showResponseAsJson(Login::getCurrentUser());
            } catch (\Exception $e) {
                return $this->showExceptionAsJson($e, $user->getMessages());
            }

        } else {
            if ($form->isValid($this->request->getPost()) === false) {
                $this->showInvalidMessages($form);
                return $this->redirectHandler('/mine/password');
            }

            try {
                $user->changePassword($this->request->getPost('password'), $this->request->getPost('passwordNew'));
                $this->flashSession->success('密码已更改');
                return $this->redirectHandler('/mine/password');
            } catch (\Exception $e) {
                $this->showException($e, $user->getMessages());
                return $this->redirectHandler('/mine/password');
            }
        }
    }

    /**
    * @operationName("更改邮箱")
    * @operationDescription("更改邮箱")
    */
    public function emailAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $form = new \Eva\EvaUser\Forms\ChangeEmailForm();
        $this->view->setVar('item', $user);
        $this->view->setVar('form', $form);

        if (!$this->request->isPost()) {
            return;
        }

        if ($this->request->isAjax()) {
            try {
                $user->requestChangeEmail($this->request->getPost('email'));
                return $this->showResponseAsJson(Login::getCurrentUser());
            } catch (\Exception $e) {
                return $this->showExceptionAsJson($e, $user->getMessages());
            }

        } else {
            try {
                $user->requestChangeEmail($this->request->getPost('email'));
                $this->flashSession->success('新邮箱验证邮件已发送，请登录邮箱验证');
                return $this->redirectHandler('/mine/email');
            } catch (\Exception $e) {
                $this->showException($e, $user->getMessages());
                return $this->redirectHandler('/mine/email');
            }
        }
    }

    /**
    * @operationName("绑定第三方账户")
    * @operationDescription("绑定第三方账户")
    */
    public function oauthAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $this->view->setVar('item', $user);

        $oauthManager = new OAuthManager();
        $tokens = $oauthManager->getUserOAuth($user->id);
        $supportedServices = array(
            'tencent' => array(
                'title' => 'QQ',
                'version' => 'oauth2',
            ),
            'weibo' => array(
                'title' => '微博',
                'version' => 'oauth2',
            ),
        );
        foreach($tokens as $token) {
            $adapterKey = $token->adapterKey;
            if(empty($supportedServices[$adapterKey])) {
                continue;
            }
            $supportedServices[$adapterKey]['token'] = $token->toArray();
        }
        $this->view->setVar('services', $supportedServices);
    }

    /**
    * @operationName("用户评论列表")
    * @operationDescription("用户评论列表")
    */
    public function commentsAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $this->view->setVar('item', $user);

        $comment = new Comment();
        $comments = $comment->findCommentsByUser($user);
        $paginator = new Paginator(array(
            "builder" => $comments,
            "limit"=> 10,
            "page" => 1
        ));
//        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);
    
    }

    /**
    * @operationName("用户收藏文章列表")
    * @operationDescription("用户收藏文章列表")
    */
    public function starsAction()
    {
        $me = Login::getCurrentUser();
        $user = User::findFirstById($me['id']);
        $this->view->setVar('item', $user);
        $userId = $user->id;

        $query = array(
            'page' => $this->request->getQuery('page', 'int', 1),
        );
        $star = new Star();
        $starsItemQuery = $star->getStars($userId);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $starsItemQuery,
            "limit"=> 5,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);
    }

    /**
    * @operationName("用户关注资产")
    * @operationDescription("用户关注资产")
    */
    public function quotesAction()
    {
    }

}
