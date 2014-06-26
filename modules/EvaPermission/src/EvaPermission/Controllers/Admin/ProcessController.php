<?php

namespace Eva\EvaPermission\Controllers\Admin;

use Eva\EvaPermission\Models;
use Eva\EvaPermission\Entities;
use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;
use Eva\EvaEngine\Exception;

class ProcessController extends ControllerBase implements JsonControllerInterface
{
    public function roleAction()
    {
        if (!$this->request->isDelete()) {
            return $this->displayJsonErrorResponse(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $data = array(
            'roleId' => $this->dispatcher->getParam('id'),
            'operationId' => $this->dispatcher->getParam('subid'),
        );
        try {
            $roleOperation =  Entities\RolesOperations::findFirst(array(
                'conditions' => 'roleId = :roleId: AND operationId = :operationId:',
                'bind' => $data,
            ));
            if($roleOperation) {
                $roleOperation->delete();
            }
        } catch (\Exception $e) {
            return $this->displayExceptionForJson($e, $roleOperation->getMessages());
        }

        return $this->response->setJsonContent($roleOperation);
    }

    public function userAction()
    {
        if (!$this->request->isDelete()) {
            return $this->displayJsonErrorResponse(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $data = array(
            'userId' => $this->dispatcher->getParam('id'),
            'roleId' => $this->dispatcher->getParam('subid'),
        );
        try {
            $userRole =  Entities\UsersRoles::findFirst(array(
                'conditions' => 'userId = :userId: AND roleId = :roleId:',
                'bind' => $data,
            ));
            if($userRole) {
                $userRole->delete();
            }
        } catch (\Exception $e) {
            return $this->displayExceptionForJson($e, $userRole->getMessages());
        }

        return $this->response->setJsonContent($userRole);
    }

    public function applyRolesAction()
    {
        if (!$this->request->isPut()) {
            return $this->displayJsonErrorResponse(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $idArray = $this->request->getPut('id');
        if (!is_array($idArray) || count($idArray) < 1) {
            return $this->displayJsonErrorResponse(401, 'ERR_REQUEST_PARAMS_INCORRECT');
        }

        $roleId = $this->request->getPut('roleId');
        $res = array();
        try {
            foreach ($idArray as $id) {
                $data = array(
                    'roleId' => $roleId,
                    'userId' => $id,
                );
                $userRole = Entities\UsersRoles::findFirst(array(
                    'conditions' => 'roleId = :roleId: AND userId = :userId:',
                    'bind' => $data,
                ));
                if(!$userRole) {
                    $userRole = new Entities\UsersRoles();
                    $userRole->assign($data);
                    $userRole->save();
                }
                $res[] = $userRole;
            }
        } catch (\Exception $e) {
            return $this->displayExceptionForJson($e, $userRole->getMessages());
        }

        return $this->response->setJsonContent($res);
    }

    public function applyOperationsAction()
    {
        if (!$this->request->isPut()) {
            return $this->displayJsonErrorResponse(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $idArray = $this->request->getPut('id');
        if (!is_array($idArray) || count($idArray) < 1) {
            return $this->displayJsonErrorResponse(401, 'ERR_REQUEST_PARAMS_INCORRECT');
        }

        $roleId = $this->request->getPut('roleid');
        $res = array();
        try {
            foreach ($idArray as $id) {
                $data = array(
                    'roleId' => $roleId,
                    'operationId' => $id,
                );
                $roleOperation =  Entities\RolesOperations::findFirst(array(
                    'conditions' => 'roleId = :roleId: AND operationId = :operationId:',
                    'bind' => $data,
                ));
                if(!$roleOperation) {
                    $roleOperation = new Entities\RolesOperations();
                    $roleOperation->assign($data);
                    $roleOperation->save();
                }
                $res[] = $roleOperation;
            }
        } catch (\Exception $e) {
            return $this->displayExceptionForJson($e, $roleOperation->getMessages());
        }

        return $this->response->setJsonContent($res);
    }
} 
