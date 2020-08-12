<?php namespace App\Controllers;

use App\Models\AuthenticationModel;

class Authentication extends BaseController
{
	protected $model, $validator, $session;

	function __construct()
	{
		helper('url');
		$this->model = new AuthenticationModel();
		$this->session = \Config\Services::session();
		$this->validation =  \Config\Services::validation();
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
		if($this->model->getSession()) return redirect('dashboard');
		else return view('pages/sign_in', ['session' =>$this->session, 'validation' => $this->validation]);
	}

	public function signIn()
	{
		$email = $this->request->getPost('email');
		$password = $this->request->getPost('password');
		$credential_error = false;
		if($this->validation->withRequest($this->request)->run()) {
			$result = $this->model->signIn($email);
			if(!empty($result) && password_verify($password, $result->password)) {
				$this->model->createSession($email);
				return redirect('dashboard');
			} else $credential_error = true;
		}
		$this->session->setFlashdata([
			'credential_error' => $credential_error,
			'email' => $email,
			'password' => $password
		]);
		return view('pages/sign_in', ['session' => $this->session, 'validation' => $this->validation]);
	}

	public function signOut()
	{
		$this->model->destroySession();
        return redirect('signin');
	}

}
