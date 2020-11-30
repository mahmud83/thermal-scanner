<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use Config\Services;

class Authentication extends BaseController
{

    public const USER_TYPE_ADMIN = 1;
    protected $model, $validator, $validation, $session;

    function __construct()
    {
        helper('url');
        $this->model = new AuthenticationModel();
        $this->session = Services::session();
        $this->validation = Services::validation();
        $this->validation->setRules([
            'email' => [
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'trim|required|min_length[4]'
            ]
        ]);
    }

    public function index()
    {
        if (!empty($this->model->getSession()->user_name)) return redirect('verify');
        else return view('pages/sign_in', ['session' => $this->session, 'validation' => $this->validation]);
    }

    public function signIn()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $userType = $this->request->getPost('type');
        $this->session->setFlashdata([
            'email' => $email,
            'password' => $password,
            'type' => $userType
        ]);
        if ($this->validation->withRequest($this->request)->run()) {
            $result = $this->model->signIn($email, $userType);
            if (!empty($result) && $userType == self::USER_TYPE_ADMIN) {
                if (password_verify($password, $result->password)) {
                    $this->model->createSession($result->name, $result->email, $result->type);
                    return redirect('verify');
                } else $this->validation->setError('password', 'Password anda salah, mohon cek kembali');
            } else $this->validation->setError('credential', true);
        }
        return view('pages/sign_in', ['session' => $this->session, 'validation' => $this->validation]);
    }

    public function signOut()
    {
        $this->model->destroySession();
        return redirect('signin');
    }

    public function verifyConnection()
    {
        if (empty($this->model->getSession()->user_name)) return redirect('signin');
        else return view('pages/verify_connection', [
            'device_key' => password_hash(getenv('device.key'), PASSWORD_DEFAULT)
        ]);
    }

}
