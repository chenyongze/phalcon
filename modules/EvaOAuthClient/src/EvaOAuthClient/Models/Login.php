<?php

namespace Eva\EvaOAuthClient\Models;

use Eva\EvaUser\Models\Login as UserLogin;
use Eva\EvaUser\Entities\Users as UserEntity;
use Eva\EvaOAuthClient\Entities\AccessTokens;
use \Phalcon\Mvc\Model\Message as Message;
use Eva\EvaEngine\Exception;

class Login extends UserEntity
{
    public function loginWithAccessToken(array $accessToken)
    {
        $accessTokenEntity = new AccessTokens();
        $accessTokenEntity->assign($accessToken);
        $token = $accessTokenEntity->findFirst(array(
            "adapterKey = :adapterKey: AND remoteUserId = :remoteUserId: AND version = :version:",
            'bind' => array(
                'adapterKey' => $accessToken['adapterKey'],
                'version' => $accessToken['version'],
                'remoteUserId' => $accessToken['remoteUserId'],
            )
        ));
        if (!$token || !$token->userId) {
            return false;
        }

        $userModel = new UserLogin();
        $userModel->assign(array(
            'id' => $token->userId
        ));

        return $userModel->login();
    }

    public function connectWithExistEmail(array $accessToken)
    {
        if (!$accessToken) {
            throw new Exception\ResourceConflictException('ERR_OAUTH_NO_ACCESS_TOKEN');
        }

        $userinfo = self::findFirst("email = '$this->email'");
        if (!$userinfo) {
            throw new Exception\ResourceNotFoundException('ERR_USER_NOT_EXIST');
        }

        if ($userinfo->status == 'deleted') {
            throw new Exception\OperationNotPermitedException('ERR_USER_BE_BANNED');
        }

        if ($userinfo->status == 'inactive') {
            $userinfo->status = 'active';
            if (!$userinfo->save()) {
                throw new Exception\RuntimeException('ERR_USER_SAVE_FAILED');
            }
        }

        $accessTokenEntity = new AccessTokens();
        $accessTokenEntity->assign($accessToken);
        $accessTokenEntity->tokenStatus = 'active';
        $accessTokenEntity->userId = $userinfo->id;
        //$this->sendVerificationEmail($userinfo->username);
        if (!$accessTokenEntity->save()) {
            throw new Exception\RuntimeException('ERR_OAUTH_TOKEN_CREATE_FAILED');
        }

        $userModel = new UserLogin();
        $authIdentity = $userModel->saveUserToSession($userinfo);

        return $authIdentity;
    }

    public function connectWithPassword($identify, $password, array $accessToken)
    {
        $userModel = new UserLogin();
        $user = $userModel->loginByPassword($identify, $password);

        $accessTokenEntity = new AccessTokens();
        $accessTokenEntity->assign($accessToken);
        $accessTokenEntity->tokenStatus = 'active';
        $accessTokenEntity->userId = $user->id;
        if (!$accessTokenEntity->save()) {
            throw new Exception\RuntimeException('ERR_OAUTH_TOKEN_CREATE_FAILED');
        }

        return $user;
    }
}
