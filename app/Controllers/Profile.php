<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\ProfileModel;
use Config\Services;

class Profile extends BaseController
{

    protected $validation, $session, $authenticationModel, $profileModel;

    function __construct()
    {
        helper('url');
        $this->authenticationModel = new AuthenticationModel();
        $this->profileModel = new ProfileModel($this->authenticationModel);
        $this->validation = Services::validation();
        $this->session = Services::session();
    }

    // GET
    public function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/edit_profile', [
            'session' => $session,
            'validation' => $this->validation,
            'title' => 'Edit Profil'
        ]);
    }

    // POST
    public function editProfile()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmNewPassword = $this->request->getPost('confirm_new_password');

        // set validation rule
        if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
            $this->validation->setRules([
                'name' => [
                    'label' => 'Name',
                    'rules' => 'trim|required'
                ],
                'email' => [
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email'
                ]
            ]);
        } else {
            $this->validation->setRules([
                'name' => [
                    'label' => 'Name',
                    'rules' => 'trim|required'
                ],
                'email' => [
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email'
                ],
                'current_password' => [
                    'label' => 'Password',
                    'rules' => 'trim|required|min_length[4]'
                ],
                'new_password' => [
                    'label' => 'Password',
                    'rules' => 'trim|required|min_length[4]'
                ],
                'confirm_new_password' => [
                    'label' => 'Password',
                    'rules' => 'trim|required|min_length[4]'
                ]
            ]);
        }

        // perform validation
        if ($this->validation->withRequest($this->request)->run()) {
            // get user data by email session
            $user = $this->profileModel->getProfile($this->authenticationModel->getSession()->user_email);
            // check user available
            if (!empty($user)) {
                // check if password empty
                if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
                    // verify email availability
                    if ($this->profileModel->verifyEmail($user->id, $user->type, $email)) {
                        // perform edit profile
                        if ($this->profileModel->editProfile($user->id, $user->type, $name, $email, null))
                            $this->session->setFlashdata(['success' => true]);
                        else $this->session->setFlashdata(['error' => 'Kesalahan telah terjadi, mohon coba ulang']);
                    } else $this->validation->setError('email', 'Email ini telah digunakan');
                } // check if new password same
                else if ($newPassword == $confirmNewPassword) {
                    // check if inputed old password is legit
                    if (password_verify($currentPassword, $user->password)) {
                        // verify email availability
                        if ($this->profileModel->verifyEmail($user->id, $user->type, $email)) {
                            // perform edit profile
                            if ($this->profileModel->editProfile($user->id, $user->type, $name, $email, $newPassword))
                                $this->session->setFlashdata(['success' => true]);
                            else $this->session->setFlashdata(['error' => 'Kesalahan telah terjadi, mohon coba ulang']);
                        } else $this->validation->setError('email', 'Email ini telah digunakan');
                    } else $this->validation->setError('current_password', 'Password anda salah, mohon cek kembali');
                } else {
                    $this->validation->setError('new_password', true);
                    $this->validation->setError('confirm_new_password', true);
                    $this->session->setFlashdata(['error' => 'Password baru anda tidak sama, mohon cek kembali']);
                }
            } else return redirect('signout');
        }

        // return to view
        return view('pages/edit_profile', [
            'session' => $this->authenticationModel->getSession(),
            'validation' => $this->validation,
            'title' => 'Edit Profil'
        ]);
    }
}