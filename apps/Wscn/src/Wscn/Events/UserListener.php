<?php

namespace Wscn\Events;

use Eva\EvaEngine\Exception;
use Eva\EvaUser\Entities\Users;
use Eva\EvaUser\Models\User;
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
        /** @var User $userInDB */
        $userInDB = $params['userInDB'];

        // 约定当 password 字段为 1234 时表示这个用户是 drupal 用户
        if ($userInDB->password != '1234') {
            return;
        }
        if (!$userInDB->oldPassword) {
            throw new Exception\RuntimeException('ERR_USER_PASSWORD_EMPTY');
        }

        if (!$dp->user_check_password($user->password, $userInDB->oldPassword)) {
            throw new Exception\VerifyFailedException('ERR_USER_PASSWORD_WRONG');
        } else {
            $userInDB->password = $userInDB::passwordHash($user->password);
            $userInDB->save();
        }
    }
}