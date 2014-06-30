<?php

namespace Eva\EvaPermission\Models;

use Eva\EvaUser\Models\Login;
use Eva\EvaEngine\Exception;

class User extends Login 
{
    const SESSION_KEY_ROLES = 'auth-roles';

    public function isSuperUser()
    {
        $authIdentity = $this->getAuthIdentity();
        if(!$authIdentity['id']) {
            return false;
        }
        $superUsers = $this->getDI()->getConfig()->permission->superusers->toArray();
        return in_array($authIdentity['id'], $superUsers) ? true : false;
    }

    public function getRoles()
    {
        $authIdentity = $this->getAuthIdentity();
        if(!$authIdentity['id']) {
            return array('GUEST');
        }
        $sessionRoles = $this->getDI()->getSession()->get(self::SESSION_KEY_ROLES);
        $sessionRoles = $sessionRoles ? $sessionRoles : array();
        //Add default roles
        $sessionRoles[] = 'USER';
        $sessionRoles = array_unique($sessionRoles);
        return $sessionRoles;
    }
}
