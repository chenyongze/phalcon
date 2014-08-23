<?php

namespace Eva\EvaOAuthClient\Models;

use Eva\EvaUser\Models\Register as UserRegister;
use Eva\EvaOAuthClient\Entities\AccessTokens;
use Eva\EvaEngine\Exception;
use Eva\EvaEngine\Mvc\Model as BaseModel;

class Register extends BaseModel
{
    public function register()
    {
        $session = $this->getDI()->getSession();
        $accessToken = OAuthManager::getAccessToken();
        if (!$accessToken) {
            throw new Exception\ResourceConflictException('ERR_OAUTH_NO_ACCESS_TOKEN');
        }

        $register = new UserRegister();
        $register->username = $this->username;
        $register->email = $this->email;
        $register->status = 'active';
        $register->accountType = 'basic';
        $register->emailStatus = 'inactive';
        $register->providerType = $accessToken['adapterKey'] . '_' . $accessToken['version'];

        $user = $register->register(true);

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
