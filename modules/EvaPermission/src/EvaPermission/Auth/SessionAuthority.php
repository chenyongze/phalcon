<?php

namespace Eva\EvaPermission\Auth;

use Phalcon\Acl\Adapter\Memory as MemoryAcl;
use Phalcon\Acl;
use Eva\EvaEngine\Exception;
use Eva\EvaPermission\Entities;
use Eva\EvaPermission\Models\User as LoginUser;
use Phalcon\Cache\Backend as BackendCache;

class SessionAuthority
{
    protected $acl;

    protected $user;

    protected $cache;

    public function getAcl()
    {
        if($this->acl) {
            return $this->acl;
        }

        $cache = $this->getCache();
        if($cache && $data = $cache->get('acl')) {
            return $this->acl = $data;
        }

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

        if($cache) {
            $cache->save('acl', $acl);
        }
        return $this->acl = $acl;
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

    public function getCache()
    {
        return $this->cache;
    }

    public function setCache(BackendCache $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    public function checkAuth($resource, $operation)
    {
        $user = $this->getUser();
        if(!$user->isUserLoggedIn()) {
            return false;
        }

        if($user->isSuperUser()) {
            return true;
        }

        $roles = $user->getRoles();
        $acl = $this->getAcl();
        foreach($roles as $role) {
            //If any of roles allowed permission
            if($acl->isAllowed($role, $resource, $operation)) {
                return true;
            }
        }
        return false;
    }
}
