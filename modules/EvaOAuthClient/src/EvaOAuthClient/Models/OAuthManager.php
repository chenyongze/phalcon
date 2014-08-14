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
}
