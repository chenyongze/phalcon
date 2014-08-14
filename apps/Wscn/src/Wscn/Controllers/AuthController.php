<?php

namespace Wscn\Controllers;

use Eva\EvaOAuthClient\Models as OAuthModels;
use Eva\EvaOAuthClient\Models\OAuthManager;
use Eva\EvaUser\Models as UserModels;
use EvaOAuth\Service as OAuthService;
use Phalcon\Mvc\View;

class AuthController extends ControllerBase
{

    /**
     * Index action
     */
    public function requestAction()
    {
        return $this->dispatcher->forward(array(
            'namespace' => 'Eva\EvaOAuthClient\Controllers',
            'controller' => 'auth',
            'action' => 'request',
        ));
    }

    public function removeAction()
    {
        $service = $this->dispatcher->getParam('service');
        $user = UserModels\Login::getCurrentUser();
        if(!$user) {
            throw new Exception\UnauthorizedException('User not login');
        }
        $oauthManager = new OAuthManager();
        try {
            $oauthManager->unbindUserOAuth($user['id'], $service);
        } catch (\Exception $e) {
            $this->showException($e);
        }
        return $this->redirectHandler('/mine/oauth');
    }

    public function accessAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('error', null);
        $this->view->setVar('token', null);
        $this->view->setVar('user', null);
        $this->view->setVar('exception', null);

        $service = $this->dispatcher->getParam('service');
        $oauthStr = $this->dispatcher->getParam('auth');
        $oauthStr = $oauthStr === 'oauth1' ? 'oauth1' : 'oauth2';
        $config = $this->getDI()->getConfig();
        $url = $this->getDI()->getUrl();
        $callback = $url->get("/auth/access/$service/$oauthStr");

        $oauth = new OAuthService();
        $oauth->setOptions(array(
            'callbackUrl' => $callback,
            'consumerKey' => $config->oauth->$oauthStr->$service->consumer_key,
            'consumerSecret' => $config->oauth->$oauthStr->$service->consumer_secret,
        ));
        $oauth->initAdapter($service, $oauthStr);
        OAuthService::setHttpClientOptions(array(
            'timeout' => 2
        ));
        $requestToken = OAuthManager::getRequestToken();

        if (!$requestToken) {
            return $this->view->setVar('error', 'ERR_OAUTH_REQUEST_TOKEN_FAILED');
        }

        try {
            $accessToken = $oauth->getAdapter()->getAccessToken($_GET, $requestToken);
            $accessTokenArray = $oauth->getAdapter()->accessTokenToArray($accessToken);
            OAuthManager::saveAccessToken($accessTokenArray);
            OAuthManager::removeRequestToken();
        } catch (\Exception $e) {
            //TODO: log exception here
            $this->view->setVar('exception', $e->__toString());
            return $this->view->setVar('error', 'ERR_OAUTH_AUTHORIZATION_FAILED');
        }

        $loginUser = UserModels\Login::getCurrentUser();
        if($loginUser) {
            $oauthManager = new OAuthManager();
            try {
                $oauthManager->bindUserOAuth($loginUser['id'], $accessTokenArray);
                OAuthManager::removeAccessToken();
                $this->view->setVar('user', $loginUser);
            } catch (\Exception $e) {
                $this->view->setVar('exception', $e->__toString());
                $this->view->setVar('error', 'ERR_OAUTH_LOGIN_FAILED');
            }
        } else {
            $accessTokenArray['suggestUsername'] = $this->getSuggestUsername($accessTokenArray);
            $accessTokenArray['suggestEmail'] = isset($accessTokenArray['remoteEmail']) ? $accessTokenArray['remoteEmail'] : '';
            $this->view->setVar('token', $accessTokenArray);
            $user = new OAuthModels\Login();
            try {
                if ($user->loginWithAccessToken($accessTokenArray)) {
                    $this->view->setVar('user', UserModels\Login::getCurrentUser());
                }
            } catch (\Exception $e) {
                $this->view->setVar('exception', $e->__toString());
                $this->view->setVar('error', 'ERR_OAUTH_LOGIN_FAILED');
            }
        }
    }

    public function registerAction()
    {
        if (!$this->request->isPost()) {
            return;
        }

        $session = $this->getDI()->getSession();
        $user = new OAuthModels\Register();
        $user->assign(array(
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
        ));

        if ($this->request->isAjax()) {
            try {
                $userinfo = $user->register();
                OAuthManager::removeAccessToken();
                $login = new UserModels\Login();
                $login->id = $userinfo->id;
                $login->login();
                return $this->showResponseAsJson(UserModels\Login::getCurrentUser());
            } catch (\Exception $e) {
                return $this->showExceptionAsJson($e, $user->getMessages());
            }
        } else {
            try {
                $userinfo = $user->register();
                OAuthManager::removeAccessToken();
                return $this->redirectHandler($this->getDI()->getConfig()->oauth->loginSuccessRedirectUri);
            } catch (\Exception $e) {
                $this->showException($e, $user->getMessages());
                return $this->redirectHandler($this->getDI()->getConfig()->oauth->registerFailedRedirectUri);
            }
        }
    }

    public function loginAction()
    {
        if (!$this->request->isPost()) {
            return;
        }

        $user = new OAuthModels\Login();
        if ($this->request->isAjax()) {
            try {
                $user->connectWithPassword(
                    $this->request->getPost('identify'),
                    $this->request->getPost('password'),
                    OAuthManager::getAccessToken()
                );
                OAuthManager::removeAccessToken();
                return $this->showResponseAsJson(UserModels\Login::getCurrentUser());
            } catch (\Exception $e) {
                return $this->showExceptionAsJson($e, $user->getMessages());
            }
        } else {
            try {
                $accessToken = OAuthManager::getAccessToken();
                $user->connectWithPassword($accessToken);
                OAuthManager::removeAccessToken();
                return $this->redirectHandler($this->getDI()->getConfig()->oauth->loginSuccessRedirectUri);
            } catch (\Exception $e) {
                $this->showException($e, $user->getMessages());
                return $this->redirectHandler($this->getDI()->getConfig()->oauth->loginFailedRedirectUri);
            }
        }

    }

    protected function getSuggestUsername($accessToken)
    {
        $suggestUsername = '';
        if (isset($accessToken['remoteUserName']) && $accessToken['remoteUserName']) {
            $suggestUsername = $accessToken['remoteUserName'];
        } elseif (isset($accessToken['remoteNickName']) && $accessToken['remoteNickName']) {
            $suggestUsername = $accessToken['remoteNickName'];
        }
        $suggestUsername = str_replace(' ', '', $suggestUsername);
        if (!$suggestUsername || !preg_match('/^[0-9a-zA-Z]+$/', $suggestUsername)) {
            return '';
        }
        return $suggestUsername;
    }

}
