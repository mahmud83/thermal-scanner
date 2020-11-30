<?php namespace App\Controllers\Rest;

use App\Controllers\BaseController;
use App\Models\DetectionModel;

class Detection extends BaseController
{
    protected $model;
    private $deviceKey;

    function __construct()
    {
        $this->model = new DetectionModel();
        $this->deviceKey = getenv('device.key') ? getenv('device.key') : '$2y$12$TD/RebWsu8LS4McyjpWtDOCBmR6I0UEJGhh3LxZhgWkZRjfvtaOb2';
    }

    // GET
    public function getDetectionList()
    {
        $key = trim($this->request->getGet('key'));
        $statusCode = 200;
        $success = true;
        $message = null;
        $data = null;
        if(password_verify($this->deviceKey, $key)) {
            $data = $this->model->getDetectionList();
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
    public function getLatestDetection()
    {
        $key = trim($this->request->getGet('key'));
        $statusCode = 200;
        $success = true;
        $message = null;
        $data = null;
        if(password_verify($this->deviceKey, $key)) {
            $data = $this->model->getLatestDetection();
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
    public function saveDetection()
    {
        $key = trim($this->request->getPost('key'));
        $ip = trim($this->request->getPost('ip'));
        $temperature = trim($this->request->getPost('temperature'));
        $statusCode = 200;
        $success = false;
        $message = null;
        if(password_verify($this->deviceKey, $key)) {
            if (empty($ip) || strlen($ip) < 8) {
                $statusCode = 400;
                $message = "invalid ip format";
            } else if (empty($temperature)) {
                $statusCode = 400;
                $message = "temperature can't be empty";
            } else if ((float)$temperature == 0) {
                $statusCode = 400;
                $message = "invalid temperature value";
            } else {
                $success = $this->model->saveDetection($ip, (float)$temperature);
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
    public function deleteDetection()
    {
        $key = trim($this->request->getGet('key'));
        $id = trim($this->request->getGet('id'));
        $statusCode = 200;
        $success = false;
        $message = null;
        if(password_verify($this->deviceKey, $key)) {
            if ((int)$id == 0) {
                $statusCode = 400;
                $message = "invalid id value";
            } else if (empty($id)) {
                $statusCode = 400;
                $message = "id can't be empty";
            } else {
                $success = $this->model->deleteDetection((int)$id);
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
    public function resetDetection()
    {
        $key = trim($this->request->getPost('key'));
        $statusCode = 200;
        $message = null;
        $success = false;
        if(password_verify($this->deviceKey, $key)) {
            $success = $this->model->resetDetection();
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
