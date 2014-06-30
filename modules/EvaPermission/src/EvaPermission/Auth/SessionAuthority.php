<?php

namespace Eva\EvaPermission\Auth;

use Phalcon\Acl\Adapter\Memory as MemoryAcl;
use Phalcon\Acl;
use Eva\EvaEngine\Exception;
use Eva\EvaPermission\Entities;
use Eva\EvaPermission\Models\User as LoginUser;

class SessionAuthority
{
    protected $acl;

    protected $user;

    public function getAcl()
    {
        return $this->acl;
    }

    public function setAcl(Acl $acl)
    {
        $this->acl = $acl;
        return $this;
    }

    public function setUser(LoginUser $user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        if(!$this->user) {
            return $this->user = new LoginUser();
        }
        return $this->user;
    }

    public function checkAuth($resource, $operation)
    {
        $roles = Entities\Roles::findFirst();
        $user = $this->getUser();

        if(!$user->isUserLoggedIn()) {
            return false;
        }

        if($user->isSuperUser()) {
            return true;
        }

        $roles = $user->getRoles();
        $acl = $this->acl;
        foreach($roles as $role) {
            //If any of roles allowed permission
            if($acl->isAllowed($role, $resource, $operation)) {
                return true;
            }
        }
        return false;
    }

    public function __construct()
    {
        $acl = new MemoryAcl();
        $acl->setDefaultAction(Acl::DENY);
        $roles = Entities\Roles::find();
        foreach($roles as $role) {
            $roleName = $role->name ? $role->name : $role->roleKey;
            $acl->addRole($role->roleKey, $role->roleKey);
        }
        $resources = Entities\Resources::find();
        foreach($resources as $resource) {
            $acl->addResource($resource->resourceKey);
        }
        $operations = Entities\Operations::find();
        foreach($operations as $operation) {
            $acl->addResourceAccess($operation->resourceKey, $operation->operationKey);
            if($operation->roles) {
                foreach($operation->roles as $role) {
                    $acl->allow($role->roleKey, $operation->resourceKey, $operation->operationKey);
                }
            }
        }
        $this->acl = $acl;
    }
}
