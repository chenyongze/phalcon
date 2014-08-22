<?php

namespace WscnApiVer2\Controllers\Admin;

use WscnApiVer2\Controllers\ControllerBase;
use Eva\EvaEngine\Mvc\Controller\TokenAuthorityControllerInterface;
use Swagger\Annotations as SWG;
use Eva\EvaUser\Models;
use Eva\EvaUser\Forms;
use Eva\EvaEngine\Exception;

/**
 * @package
 * @category
 * @subpackage
 *
 * @SWG\Resource(
 *  apiVersion="0.2",
 *  swaggerVersion="1.2",
 *  resourcePath="/AdminUsers",
 *  basePath="/v2"
 * )
 */
class UsersController extends ControllerBase implements TokenAuthorityControllerInterface
{
    /**
     *
     * @SWG\Api(
     *   path="/admin/users",
     *   description="User manage api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="Get user list",
     *       notes="Returns user list with paginator",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="username",
     *           description="Username",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="status",
     *           description="Status, allow value : pending | published | deleted | draft",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="order",
     *           description="Order, allow value : +-id, +-created_at, default is -created_at",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="limit",
     *           description="Limit max:100 | min:3; default is 25",
     *           paramType="query",
     *           required=false,
     *           type="integer"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function indexAction()
    {
        $limit = $this->request->getQuery('limit', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 3 ? 3 : $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'status' => $this->request->getQuery('status', 'string'),
            'uid' => $this->request->getQuery('uid', 'int'),
            'cid' => $this->request->getQuery('cid', 'int'),
            'username' => $this->request->getQuery('username', 'string'),
            'order' => $order,
            'limit' => $limit,
            'page' => $this->request->getQuery('page', 'int', 1),
        );

        $form = new Forms\FilterForm();
        $form->setValues($this->request->getQuery());

        $user = new Models\UserManager();
        $users = $user->findUsers($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $users,
            "limit"=> $limit,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();

        $userArray = array();
        if ($pager->items) {
            foreach ($pager->items as $key => $user) {
                $userArray[] = $user->dump(Models\UserManager::$defaultDump);
            }
        }

        $data = array(
            'paginator' => $this->getApiPaginator($paginator),
            'results' => $userArray,
        );
        return $this->response->setJsonContent($data);
    }

    /**
    *
    * @SWG\Api(
    *   path="/admin/users/{userId}",
    *   description="User related api",
    *   produces="['application/json']",
    *   @SWG\Operations(
    *     @SWG\Operation(
    *       method="GET",
    *       summary="Find user by ID",
     *       notes="Returns a user based on ID",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="userId",
     *           description="ID of user",
     *           paramType="path",
     *           required=true,
     *           type="integer"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function getAction()
    {
        $id = $this->dispatcher->getParam('id');
        $userModel = new Models\UserManager();
        $user = $userModel->findFirst($id);
        if (!$user) {
            throw new Exception\ResourceNotFoundException('Request user not exist');
        }
        $user = $user->dump(Models\UserManager::$defaultDump);
        return $this->response->setJsonContent($user);
    }

    /**
     *
     * @SWG\Api(
     *   path="/admin/users/{userId}",
     *   description="User related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="PUT",
     *       summary="Update user by ID",
     *       notes="Returns updated user",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="userId",
     *           description="ID of user",
     *           paramType="path",
     *           required=true,
     *           type="integer"
     *         )
     *       ),
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="userData",
     *           description="User info",
     *           paramType="body",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function putAction()
    {
        $id = $this->dispatcher->getParam('id');
        $data = $this->request->getRawBody();
        if (!$data) {
            throw new Exception\InvalidArgumentException('No data input');
        }
        if (!$data = json_decode($data, true)) {
            throw new Exception\InvalidArgumentException('Data not able to decode as JSON');
        }
        $user = Models\UserManager::findFirst($id);
        if (!$user) {
            throw new Exception\ResourceNotFoundException('Request user not exist');
        }
        $form = new Forms\UserForm();
        $form->setModel($user);
        $form->addForm('profile', 'Eva\EvaUser\Forms\ProfileForm');
        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessagesAsJson($form);
        }
        try {
            $form->save('updateUser');
            $data = $user->dump(Models\UserManager::$defaultDump);
            return $this->response->setJsonContent($data);
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $form->getModel()->getMessages());
        }
    }

     /**
     *
     * @SWG\Api(
     *   path="/admin/users",
     *   description="User related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="POST",
     *       summary="Create new user",
     *       notes="Returns a user based on ID",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="user json",
     *           description="User info",
     *           paramType="body",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function postAction()
    {
        $data = $this->request->getRawBody();
        if (!$data) {
            throw new Exception\InvalidArgumentException('No data input');
        }
        if (!$data = json_decode($data, true)) {
            throw new Exception\InvalidArgumentException('Data not able to decode as JSON');
        }

        $form = new Forms\UserForm();
        $user = new Models\UserManager();
        $form->setModel($user);
        $form->addForm('profile', 'Eva\EvaUser\Forms\ProfileForm');

        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessagesAsJson($form);
        }

        try {
            $form->save('createUser');
            $data = $user->dump(Models\UserManager::$defaultDump);
            return $this->response->setJsonContent($data);
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $form->getModel()->getMessages());
        }
    }

    /**
    *
     * @SWG\Api(
     *   path="/admin/users/{userId}",
     *   description="User related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="DELETE",
     *       summary="Delete user by ID",
     *       notes="Returns deleted user",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="userId",
     *           description="ID of user",
     *           paramType="path",
     *           required=true,
     *           type="integer"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function deleteAction()
    {
         $id = $this->dispatcher->getParam('id');
         $user = Models\UserManager::findFirst($id);
        if (!$user) {
            throw new Exception\ResourceNotFoundException('Request user not exist');
        }
         $userinfo = $user->dump(Models\UserManager::$defaultDump);
        try {
            $user->removeUser($id);
            return $this->response->setJsonContent($userinfo);
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $user->getMessages());
        }
    }
}
