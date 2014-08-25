<?php

namespace WscnApiVer2\Controllers;

use Swagger\Annotations as SWG;
use Eva\EvaEngine\Exception;
use Eva\EvaUser\Forms\LoginForm;
use Eva\EvaUser\Models\Login;
use Eva\EvaUser\Models\User;
use Eva\EvaPermission\Models\Apikey;


/**
 * @package
 * @category
 * @subpackage
 *
 * @SWG\Resource(
 *  apiVersion="0.2",
 *  swaggerVersion="1.2",
 *  resourcePath="/Login",
 *  basePath="/v2"
 * )
 */
class LoginController extends ControllerBase
{
     /**
     *
     * @SWG\Api(
     *   path="/login",
     *   description="User Login API",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="POST",
     *       summary="Login by password",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="Login json",
     *           description="{ identify : username or email, password : password}",
     *           paramType="body",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function indexAction()
    {
        Login::setLoginMode(Login::LOGIN_MODE_TOKEN);
        $data = $this->request->getRawBody();
        if (!$data) {
            throw new Exception\InvalidArgumentException('No data input');
        }
        if (!$data = json_decode($data, true)) {
            throw new Exception\InvalidArgumentException('Json data parsing failed');
        }

        $form = new LoginForm();
        if ($form->isValid($data) === false) {
            return $this->showInvalidMessagesAsJson($form);
        }

        $user = new Login();
        $apikey = new Apikey();
        $loginUser = $user->loginByPassword($data['identify'], $data['password']);
        $userinfo = $loginUser->dump(User::$simpleDump);
        $userinfo['roles'] = Login::getAuthStorage()->get(Login::AUTH_KEY_ROLES);
        $userinfo['token'] = Login::getAuthStorage()->get(Login::AUTH_KEY_TOKEN);
        return $this->response->setJsonContent($userinfo);
    }
}
