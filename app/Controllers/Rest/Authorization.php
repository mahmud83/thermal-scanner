<?php namespace App\Controllers\Rest;

use App\Controllers\BaseController;
use Heimdall\Interfaces\AuthorizationController;
use App\Libraries\OAuth2\Entities\UserEntity;
use App\Libraries\OAuth2\HeimdallAuthorizationCode;
use Exception;

class Authorization extends BaseController implements AuthorizationController
{
    private $heimdall;

    function __construct()
    {
        $this->heimdall = HeimdallAuthorizationCode::createAuthorizationServer();
        $this->heimdall->bootstrap($this->request, $this->response);
    }

    function authorize()
    {
        try {
            $authRequest = $this->heimdall->validateAuth();
            $authRequest->setUser(new UserEntity());
            $this->heimdall->completeAuth($authRequest);
        } catch (Exception $exception) {
            $this->heimdall->handleException($exception);
        }
    }

    function token()
    {
        try {
            $this->heimdall->createToken();
        } catch (Exception $exception) {
            $this->heimdall->handleException($exception);
        }
    }
}