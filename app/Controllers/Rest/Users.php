<?php namespace App\Controllers\Rest;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Users extends BaseController
{
    use ResponseTrait;

    function getUsers()
    {
        return $this->respond(['name' => 'Ezra Lazuardy']);
    }
}