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
            throw new Exception\RuntimeException('ERR_USER_CHANGE_PASSWORD_FAILED');
        }
        return $user;
    }

    public function changeAvatar()
    {
    }

    public function changeProfile($data)
    {

    
    }

    public function requestChangeEmail($newEmail, $forceSend = false)
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
        return $this->sendChangeEmailVerificationEmail($user->username, $newEmail);
    }

    public function sendChangeEmailVerificationEmail($username, $newEmail, $forceSend = false)
    {
        if (false === $forceSend && $this->getDI()->getConfig()->mailer->async) {
            $queue = $this->getDI()->getQueue();
            $result = $queue->doBackground('sendmailAsync', json_encode(array(
                'class' => __CLASS__,
                'method' => __FUNCTION__,
                'parameters' => array($username, $newEmail, true)
            )));
            return true;
        }

        $user = self::findFirst("username = '$username'");
        if (!$user) {
            throw new Exception\ResourceNotFoundException('ERR_USER_NOT_EXIST');
        }

        $mailer = $this->getDI()->getMailer();
        $message = $this->getDI()->getMailMessage();
        $message->setTo(array(
            $newEmail => $user->username
        ));

        //Change email hash will expired when password / email changed
        $verifyCode = md5($user->id . $user->password . $user->email . $newEmail);
        $message->setTemplate($this->getDI()->getConfig()->user->changeEmailTemplate);
        $message->assign(array(
            'user' => $user->toArray(),
            'url' => $message->toSystemUrl('/session/changemail/' . urlencode($user->username) . '/' . urlencode($newEmail) . '/' . $verifyCode)
        ));

        $mailer->send($message->getMessage());
    }

    public function changeEmail($username, $newEmail, $verifyCode)
    {
        $user = self::findFirst("username = '$username'");
        if (!$user) {
            throw new Exception\ResourceNotFoundException('ERR_USER_NOT_EXIST');
        }

        $hash = md5($user->id . $user->password . $user->email . $newEmail);
        if($hash !== $verifyCode) {
            throw new Exception\VerifyFailedException('ERR_USER_CHANGE_EMAIL_VERIFY_CODE_NOT_MATCH');
        }

        $user->email = $newEmail;
        if(!$user->save()) {
            throw new Exception\RuntimeException('ERR_USER_CHANGE_EMAIL_FAILED');
        }

        return $user;
    }

}
