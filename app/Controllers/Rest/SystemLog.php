<?php namespace App\Controllers\Rest;

use App\Controllers\BaseController;
use App\Models\SystemLogModel;

class SystemLog extends BaseController
{

    protected $model;

    function __construct()
    {
        $this->model = new SystemLogModel();
    }

    // GET
    public function getLogList()
    {
        $key = trim($this->request->getGet('key'));
        $statusCode = 200;
        $success = true;
        $message = null;
        $data = null;
        if ($key == getenv('device.key')) {
            $data = $this->model->getLogList();
            if ($data == null) {
                $statusCode = 500;
                $success = false;
                $message = "internal server error";
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

    // GET
    public function getLatestLog()
    {
        $key = trim($this->request->getGet('key'));
        $statusCode = 200;
        $success = true;
        $message = null;
        $data = null;
        if ($key == getenv('device.key')) {
            $data = $this->model->getLatestLog();
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
    public function saveLog()
    {
        $key = trim($this->request->getPost('key'));
        $ip = trim($this->request->getPost('ip'));
        $event = strtolower(trim($this->request->getPost('event')));
        $type = strtolower(trim($this->request->getPost('type')));
        $statusCode = 200;
        $success = false;
        $message = null;
        if ($key == getenv('device.key')) {
            if (empty($ip) || strlen($ip) < 7) {
                $statusCode = 400;
                $message = "invalid ip format";
            } else if (empty($event)) {
                $statusCode = 400;
                $message = "event cant be empty";
            } else if ($type != 'standard' || $type != 'warning' || $type != 'error') {
                $statusCode = 400;
                $message = "unknown log type";
            } else {
                $success = $this->model->saveLog($ip, $event, $type);
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

    // GET
    public function resetLog()
    {
        $key = trim($this->request->getGet('key'));
        $statusCode = 200;
        $message = null;
        $success = false;
        if ($key == getenv('device.key')) {
            $success = $this->model->resetLog();
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
