<?php

namespace WscnApiVer2\Controllers;

use Swagger\Annotations as SWG;
use Eva\EvaEngine\Exception;
use Eva\EvaUser\Forms\LoginForm;
use Eva\EvaUser\Models\Login;
use Eva\EvaUser\Models\User;
use Eva\EvaPermission\Models\Apikey;
use Eva\EvaEngine\Mvc\Controller\TokenAuthorityControllerInterface;


/**
 * @package
 * @category
 * @subpackage
 *
 * @SWG\Resource(
 *  apiVersion="0.2",
 *  swaggerVersion="1.2",
 *  resourcePath="/Users",
 *  basePath="/v2"
 * )
 * @resourceName("用户API")
 * @resourceDescription("用户API")
 */
class UsersController extends ControllerBase implements TokenAuthorityControllerInterface
{
     /**
     *
     * @SWG\Api(
     *   path="/users/me",
     *   description="User API",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="Get current user info",
     *     )
     *   )
     * )
     */
    public function indexAction()
    {
        Login::setLoginMode(Login::LOGIN_MODE_TOKEN);
        $storage = Login::getAuthStorage();
        $userinfo = Login::getCurrentUser();
        return $this->response->setJsonContent($userinfo);
    }
}
