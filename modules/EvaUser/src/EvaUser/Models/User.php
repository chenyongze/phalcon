<?php

namespace Eva\EvaUser\Models;

use Eva\EvaUser\Entities;
use Eva\EvaEngine\Exception;

class User extends Entities\Users
{
    public function getAvatar()
    {
    }

    public function changePassword($oldPassword, $newPassword)
    {
        $me = Login::getCurrentUser();
        $userId = $me['id'];
        if(!$userId) {
            throw new Exception\UnauthorizedException('ERR_USER_NOT_LOGIN');
        }

        $user = self::findFirst("id = $userId");
        if (!$user) {
            throw new Exception\ResourceNotFoundException('ERR_USER_NOT_EXIST');
        }

        if(false === password_verify($oldPassword, $user->password)) {
            throw new Exception\VerifyFailedException('ERR_USER_OLD_PASSWORD_NOT_MATCH');
        }

        $user->password = password_hash($newPassword, PASSWORD_DEFAULT, array('cost' => 10));
        if(!$user->save()) {
            throw new Exception\RuntimeException('Change user password failed');
        }
        return $user;
    }

    public function changeAvatar()
    {
    }

    public function changeProfile($data)
    {
    
    }

    public function changeEmail($newEmail)
    {
    }

    public function verifyNewEmail($verifyCode)
    {
    
    }

}
