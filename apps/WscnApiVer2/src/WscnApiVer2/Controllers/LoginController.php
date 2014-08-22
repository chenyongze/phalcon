<?php

namespace WscnApiVer2\Controllers;

use Swagger\Annotations as SWG;
use Eva\EvaEngine\Exception;
use Eva\EvaUser\Forms\LoginForm;
use Eva\EvaUser\Models\Login;
use Eva\EvaUser\Models\User;


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
        try {
            $loginUser = $user->loginByPassword($data['identify'], $data['password']);
            return $this->showResponseAsJson($loginUser->dump(User::$simpleDump));
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $user->getMessages());
        }
    }

}
