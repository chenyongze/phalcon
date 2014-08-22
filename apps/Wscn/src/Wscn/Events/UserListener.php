<?php

namespace Wscn\Events;

use Eva\EvaEngine\Exception;
use Eva\EvaUser\Entities\Users;
use Phalcon\Events\Event;
use Wscn\Utils\DrupalPasswod;
use Wscn\Utils\UserExtensionInfo;

class UserListener
{
    public function __construct()
    {

    }

    public function beforeVerifyPassword(Event $event, $params)
    {
        $dp = new DrupalPasswod();
        $user = $params['user'];
        $userInDB = $params['userInDB'];

        if ($userInDB->password) {
            return;
        }
        if (!$userInDB->oldPassword) {
            throw new Exception\RuntimeException('ERR_USER_PASSWORD_EMPTY');
        }

        if (!$dp->user_check_password($user->password, $userInDB->oldPassword)) {
            $user->verifiedByEventHandlers = false;
            throw new Exception\VerifyFailedException('ERR_USER_PASSWORD_WRONG');
        } else {
            $user->verifiedByEventHandlers = true;
        }
    }
}