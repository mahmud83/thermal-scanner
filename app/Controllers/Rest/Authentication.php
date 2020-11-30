<?php namespace App\Controllers\Rest;

use App\Controllers\BaseController;
use App\Models\AuthenticationModel;

class Authentication extends BaseController
{
    protected $model;
    private $deviceKey;

    function __construct()
    {
        $this->model = new AuthenticationModel();
        $this->deviceKey = getenv('device.key') ? getenv('device.key') : '$2y$12$TD/RebWsu8LS4McyjpWtDOCBmR6I0UEJGhh3LxZhgWkZRjfvtaOb2';
    }

    // GET
    public function getAck()
    {
        $key = trim($this->request->getGet('key'));
        $statusCode = 200;
        $success = true;
        $message = null;
        $data = null;
        if(password_verify($this->deviceKey, $key)) {
            $data = $this->model->getAck();
            if ($data == null) {
                $statusCode = 404;
                $success = true;
                $message = "ack request is empty";
            }
        } else {
            $statusCode = 401;
            $success = false;
            $message = "invalid key";
        }
        return $this->response->setJSON([
            "success" => $success,
            "message" => $message,
            "data" => $data
        ])->setStatusCode($statusCode);
    }

    // POST
    public function sendAck()
    {
        $key = trim($this->request->getPost('key'));
        $ip = trim($this->request->getPost('ip'));
        $statusCode = 200;
        $success = false;
        $message = null;
        if(password_verify($this->deviceKey, $key)) {
            if (empty($ip) || strlen($ip) < 7) {
                $statusCode = 400;
                $message = "invalid ip format";
            } else {
                $success = $this->model->sendAck($ip);
                if (!$success) {
                    $statusCode = 500;
                    $message = "internal server error";
                }
            }
        } else {
            $statusCode = 401;
            $message = "invalid key";
        }
        return $this->response->setJSON([
            "success" => $success,
            "message" => $message
        ])->setStatusCode($statusCode);
    }

    // POST
    public function resetAck()
    {
        $key = trim($this->request->getPost('key'));
        $statusCode = 200;
        $success = false;
        $message = null;
        if(password_verify($this->deviceKey, $key)) {
            $success = $this->model->resetAck();
            if (!$success) {
                $statusCode = 500;
                $message = "internal server error";
            }
        } else {
            $statusCode = 401;
            $message = "invalid key";
        }
        return $this->response->setJSON([
            "success" => $success,
            "message" => $message
        ])->setStatusCode($statusCode);
    }

}
