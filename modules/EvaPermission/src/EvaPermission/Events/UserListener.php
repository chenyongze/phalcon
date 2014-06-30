<?php

namespace Eva\EvaPermission\Events;

use Eva\EvaEngine\Exception;
use Eva\EvaEngine\Mvc\Controller\SessionAuthorityControllerInterface;
use Eva\EvaPermission\Entities;
use Eva\EvaPermission\Auth;
use Eva\EvaPermission\Models\User;

class UserListener
{
    public function afterLogin($event, $loginUser)
    {
        if(!$loginUser->id) {
            return;
        }

        $roles = $loginUser->roles;
        $sessionRoles = array();
        if($roles) {
            foreach($roles as $role) {
                $sessionRoles[] = $role->roleKey;
            }
        }
        $loginUser->getDI()->getSession()->set(User::SESSION_KEY_ROLES, $sessionRoles);
    }
}
