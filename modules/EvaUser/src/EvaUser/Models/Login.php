<?php

namespace Eva\EvaUser\Models;

use Eva\EvaUser\Entities;
use Phalcon\Mvc\Model\Message as Message;
use Eva\EvaEngine\Exception;
use Phalcon\DI;

class Login extends Entities\Users
{
    const SESSION_KEY_LOGIN = 'auth-identity';
    const SESSION_KEY_ROLES = 'auth-roles';

    private $rememberMeTokenSalt = 'EvaUser_Login_TokenSalt';

    protected $rememberMeTokenExpires = 5184000; //60 days

    protected $maxLoginRetry = 5;

    public static function getCurrentUser()
    {
        $di = DI::getDefault();
        $session = $di->getSession();
        $currentUser = $session->get(self::SESSION_KEY_LOGIN);
        if ($currentUser) {
            return $currentUser;
        }
        return array(
            'id' => 0,
            'username' => 'Guest',
            'status' => '',
            'email' => '',
            'avatar' => '',
        );
    }

    public static function getCurrentUserRoles()
    {
        $di = DI::getDefault();
        $session = $di->getSession();
        $roles = $session->get(self::SESSION_KEY_ROLES);
        if ($roles) {
            return $roles;
        }
        return array(
            'GUEST'
        );
    }

    public function getRememberMeTokenExpires()
    {
        return $this->rememberMeTokenExpires;
    }

    public function setRememberMeTokenExpire($rememberMeTokenExpires)
    {
        $this->rememberMeTokenExpires = $rememberMeTokenExpires;
        return $this;
    }

    public function getRememberMeHash(Entities\Users $userinfo)
    {
        //If user password or status changed, all user token will be unavailable
        return md5($this->tokenSalt . $userinfo->status .  $userinfo->password);
    }

    public function getRememberMeToken()
    {
        if (!$this->username) {
            $this->appendMessage(new Message('ERR_USER_REMEMBER_TOKEN_NO_USER_INPUT'));
            return false;
        }

        $sessionId = $this->getDI()->getSession()->getId();
        if (!$sessionId) {
            $this->appendMessage(new Message('ERR_USER_REMEMBER_TOKEN_NO_SESSION'));
            return false;
        }

        $userinfo = self::findFirst("username = '$this->username'");
        if (!$userinfo) {
            $this->appendMessage(new Message('ERR_USER_REMEMBER_TOKEN_USER_NOT_FOUND'));
            return false;
        }

        $token = new Entities\Tokens();
        $token->sessionId = $sessionId;
        $token->token = md5(uniqid(rand(), true));
        $token->userHash = $this->getRememberMeHash($userinfo);
        $token->userId = $userinfo->id;
        $token->refreshAt = time();
        $token->expiredAt = time() + $this->tokenExpired;
        $token->save();
        $tokenString = $sessionId . '|' . $token->token . '|' . $token->userHash;

        return $tokenString;
    }


    public function saveUserToSession(Entities\Users $userinfo)
    {
        $authIdentity = $this->userToAuthIdentity($userinfo);
        $this->getDI()->getSession()->set(self::SESSION_KEY_LOGIN, $authIdentity);

        return $authIdentity;
    }

    public function userToAuthIdentity(Entities\Users $userinfo)
    {
        return array(
            'id' => $userinfo->id,
            'username' => $userinfo->username,
            'status' => $userinfo->status,
            'email' => $userinfo->email,
            'avatar' =>  'http://www.gravatar.com/avatar/' . md5(strtolower(trim($userinfo->email)))
        );
    }

    /**
    * System login
    * 1. Check user exsits
    * 2. Clear user login failde counter
    * 3. Update user last login time
    * 4. Save user info to Session
    *
    * @return Login
    */
    public function login()
    {
        $this->getDI()->getEventsManager()->fire('user:beforeLogin', $this);

        if (!$this->id) {
            throw new Exception\InvalidArgumentException('ERR_USER_NO_ID_INPUT');
        }

        $userinfo = array();
        if ($this->id) {
            $userinfo = self::findFirst("id = '$this->id'");
        }
        if (!$userinfo) {
            throw new Exception\ResourceNotFoundException('ERR_USER_NOT_EXIST');
        }

        if ($userinfo->status != 'active') {
            throw new Exception\UnauthorizedException('ERR_USER_NOT_ACTIVED');
        }

        $userinfo->failedLogins = 0;
        $userinfo->loginAt = time();
        $userinfo->save();
        $authIdentity = $this->saveUserToSession($userinfo);

        $this->getDI()->getEventsManager()->fire('user:afterLogin', $userinfo);

        return $userinfo;
    }

    /**
    * Login by Password
    *
    * @param $identify  username or email
    * @param $password  user password
    * @return Login
    */
    public function loginByPassword($identify, $password)
    {
        if (false === strpos($identify, '@')) {
            $this->assign(array(
                'username' => $identify,
                'password' => $password,
            ));
        } else {
            $this->assign(array(
                'email' => $identify,
                'password' => $password
            ));
        }
        $this->getDI()->getEventsManager()->fire('user:beforeLoginByPassword', $this);

        //Check password process
        $userinfo = array();
        if ($this->username) {
            $userinfo = self::findFirst("username = '$this->username'");
        } elseif ($this->email) {
            $userinfo = self::findFirst("email = '$this->email'");
        } else {
            throw new Exception\InvalidArgumentException('ERR_USER_NO_USERNAME_OR_EMAIL_INPUT');
        }

        if (!$userinfo) {
            throw new Exception\ResourceNotFoundException('ERR_USER_NOT_EXIST');
        }

        if ($userinfo->failedLogins >= $this->maxLoginRetry && $userinfo->loginFailedAt > (time() - 30)) {
            throw new Exception\RuntimeException('ERR_USER_PASSWORD_WRONG_MAX_TIMES');
        }

        if (!$userinfo->password) {
            throw new Exception\RuntimeException('ERR_USER_PASSWORD_EMPTY');
        }

        // check if hash of provided password matches the hash in the database
        if (!password_verify($this->password, $userinfo->password)) {
            //MUST be string type here
            $userinfo->failedLogins = (string) ($userinfo->failedLogins + 1);
            $userinfo->loginFailedAt = time();
            $userinfo->save();
            throw new Exception\VerifyFailedException('ERR_USER_PASSWORD_WRONG');
        }

        $login = new Login();
        $login->id = $userinfo->id;
        return $login->login();
    }

    public function loginByCookie($tokenString)
    {
        $this->getDI()->getEventsManager()->fire('user:beforeLoginByCookie', $tokenString);

        $tokenArray = explode('|', $tokenString);
        if (!$tokenArray || count($tokenArray) < 3) {
            $this->appendMessage(new Message('ERR_USER_REMEMBER_TOKEN_FORMAT_INCORRECT'));
            return false;
        }

        $token = new Entities\Tokens();
        $token->assign(array(
            'sessionId' => $tokenArray[0],
            'token' => $tokenArray[1],
            'userHash' => $tokenArray[2],
        ));
        $tokenInfo = $token::findFirst();
        if (!$tokenInfo) {
            $this->appendMessage(new Message('ERR_USER_REMEMBER_TOKEN_NOT_FOUND'));
            return false;
        }

        if ($tokenInfo->expiredAt < time()) {
            $this->appendMessage(new Message('ERR_USER_REMEMBER_TOKEN_EXPIRED'));
            return false;
        }

        $login = new Login();
        $login->id = $tokenInfo->userId;
        return $login->login();
    }

    public function getAuthIdentity()
    {
        $authIdentity = $this->getDI()->getSession()->get(self::SESSION_KEY_LOGIN);
        if ($authIdentity) {
            return $authIdentity;
        }
        return false;
    }

    /**
     * Returns the current state of the user's login
     * @return bool user's login status
     */
    public function isUserLoggedIn()
    {
        return $this->getAuthIdentity() ? true : false;
    }
}
