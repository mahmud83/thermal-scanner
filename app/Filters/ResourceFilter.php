<?php namespace App\Filters;

use App\Libraries\OAuth2\HeimdallAuthorizationCode;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Exception;

class ResourceFilter implements FilterInterface
{
    private $heimdall;

    function __construct()
    {
        $this->heimdall = HeimdallAuthorizationCode::createResourceServer();
    }

    function before(RequestInterface $request, $arguments = null)
    {
        try {
            $this->heimdall->validate($request);
        } catch (Exception $exception) {
            $this->heimdall->handleException($exception);
        }
    }

    function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    { }
}