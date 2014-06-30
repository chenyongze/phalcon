<?php

namespace Eva\EvaPermission\Models;

use Eva\EvaPermission\Entities;
use Eva\EvaEngine\Exception;

class Apikey extends Entities\Apikeys
{
    protected $token;

    public function beforeValidationOnCreate()
    {
        $this->createdAt = time();
        $this->apikey = \Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, 8);
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function isSuperToken()
    {
        if(!$this->token) {
            return false;
        }
        $config = $this->getDI()->getConfig()->permission->superkeys->toArray();
        if(in_array($this->token, $config)) {
            return true;
        }
        return false;
    }

    public function getTokenStatus()
    {
        $token = $this->token;
        if(!$token) {
            return array();
        }
        $fastCache = $this->getDI()->getFastCache();
        $cacheKey = 'eva-permission-token-' . $token;
        if($fastCache && $data = $fastCache->get($cacheKey)) {
            return json_decode($data, true);
        }

        $tokenObj = self::findFirst("apikey = '$token'");
        if($tokenObj) {
            $tokenObj = $tokenObj;
            $token = array(
                'apikey' => $tokenObj->apikey,
                'userId' => $tokenObj->userId,
                'level' => $tokenObj->level,
                'minutelyRate' => $tokenObj->minutelyRate,
                'hourlyRate' => $tokenObj->hourlyRate,
                'dailyRate' => $tokenObj->dailyRate,
                'expiredAt' => $tokenObj->expiredAt,
            );
            $roles = array();
            if($tokenObj->user && $userRoles = $tokenObj->user->roles) {
                foreach($userRoles as $role) {
                    $roles[] = $role->roleKey;
                }
            }
            if($tokenObj->user->status == 'active') {
                $roles[] = 'USER';
                $roles = array_unique($roles);
            }
            $token['roles'] = $roles;
        } else {
            $token = array();
        }

        $fastCache->set($cacheKey, json_encode($token));
        return $token;
    }

    public function isOutOfMinutelyRate()
    {
    
    }


}
