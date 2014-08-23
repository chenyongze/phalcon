<?php

namespace Eva\EvaOAuthClient\Models;

use Phalcon\DI;
use Eva\EvaEngine\Exception;
use Eva\EvaOAuthClient\Entities\AccessTokens;
use Eva\EvaEngine\Mvc\Model as BaseModel;

class OAuthManager extends BaseModel
{
    const REQUEST_TOKEN_KEY = 'oauth-request-token';
    const ACCESS_TOKEN_KEY = 'oauth-access-token';
    
    public static function getRequestToken()
    {
        $session = DI::getDefault()->getSession();
        return $session->get(self::REQUEST_TOKEN_KEY);
    }

    public static function saveRequestToken($token)
    {
        $session = DI::getDefault()->getSession();
        $session->set(self::REQUEST_TOKEN_KEY, $token);
    }

    public static function removeRequestToken()
    {
        $session = DI::getDefault()->getSession();
        $session->remove(self::REQUEST_TOKEN_KEY);
    }

    public static function getAccessToken()
    {
        $session = DI::getDefault()->getSession();
        return $session->get(self::ACCESS_TOKEN_KEY);
    }

    public static function saveAccessToken($token)
    {
        $session = DI::getDefault()->getSession();
        $session->set(self::ACCESS_TOKEN_KEY, $token);
    }

    public static function removeAccessToken()
    {
        $session = DI::getDefault()->getSession();
        $session->remove(self::ACCESS_TOKEN_KEY);
    }

    public function getUserOAuth($userId)
    {
        $tokens = AccessTokens::find(array(
            "conditions" => "userId = :userId:",
            "bind"       => array(
                'userId' => $userId
            )
        ));
        return $tokens;
    }

    public function bindUserOAuth($userId, array $accessToken)
    {
        $token = AccessTokens::findFirst(array(
            "conditions" => "adapterKey = :adapterKey: AND version = :version: AND remoteUserId = :remoteUserId:",
            "bind"       => array(
                'adapterKey' => $accessToken['adapterKey'],
                'version' => $accessToken['version'],
                'remoteUserId' => $accessToken['remoteUserId'],
            )
        ));
        if ($token) {
            $token->userId = $userId;
            if (!$token->save()) {
                throw new Exception\RuntimeException('ERR_OAUTH_TOKEN_UPDATE_FAILED');
            }
            return $token;
        } else {
            $accessTokenEntity = new AccessTokens();
            $accessTokenEntity->assign($accessToken);
            $accessTokenEntity->tokenStatus = 'active';
            $accessTokenEntity->userId = $userId;
            if (!$accessTokenEntity->save()) {
                throw new Exception\RuntimeException('ERR_OAUTH_TOKEN_UPDATE_FAILED');
            }
            return $token;
        }
    }

    public function unbindUserOAuth($userId, $adapterKey)
    {
        $tokens = AccessTokens::find(array(
            "conditions" => "userId = :userId: AND adapterKey = :adapterKey:",
            "bind"       => array(
                'userId' => $userId,
                'adapterKey' => $adapterKey,
            )
        ));
        if ($tokens) {
            foreach ($tokens as $token) {
                $token->delete();
            }
        }
        return $tokens;
    }
}
