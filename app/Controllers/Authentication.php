<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use Config\Services;

class Authentication extends BaseController
{

    protected $model, $validation, $session;

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

    // GET
    public function index()
    {
        if (!empty($this->model->getSession()->user_name)) return redirect('dashboard');
        else return view('pages/sign_in', ['session' => $this->session, 'validation' => $this->validation]);
    }

    // POST
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
            $user = $this->model->signIn($email, $userType);
            if (!empty($user) && $userType == $user->type) {
                if (password_verify($password, $user->password)) {
                    $this->model->createSession($user->name, $user->email, $user->type, $user->created_on);
                    return redirect('dashboard');
                } else $this->validation->setError('password', 'Password anda salah, mohon cek kembali');
            } else $this->validation->setError('credential', true);
        }
        return view('pages/sign_in', ['session' => $this->session, 'validation' => $this->validation]);
    }

    // GET
    public function signOut()
    {
        $this->model->destroySession();
        return redirect('signin');
    }
}
