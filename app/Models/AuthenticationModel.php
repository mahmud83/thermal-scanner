<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;
use Config\Services;

class AuthenticationModel extends Model
{

    protected $db, $session, $profileModel;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->session = Services::session();
        $this->profileModel = new ProfileModel($this);
    }

    public function getSession()
    {
        $session = $this->session;
        $user = $this->profileModel->getProfile($session->user_email);
        return !empty($user) && $user->created_on == $session->created_on ? $this->session : null;
    }

    public function createSession(string $name, string $email, string $userType, string $createdOn)
    {
        $studyProgram = $this->profileModel->getStudyProgramProfile($email);
        $data = [
            'user_name' => $name,
            'user_email' => $email,
            'user_type' => $userType,
            'created_on' => $createdOn
        ];
        if(!empty($studyProgram['id'])) $data['user_study_program_id'] = $studyProgram['id'];
        if(!empty($studyProgram['name'])) $data['user_study_program_name'] = $studyProgram['name'];
        $this->session->set($data);
    }

    public function destroySession()
    {
        $this->session->destroy();
    }

    public function signIn(string $email, int $userType)
    {
        return $this->db->table('user')
            ->select('user.email, user.password, user.type, user.name, user.created_on')
            ->getWhere([
                'user.type' => $userType,
                'lower(trim(user.email))' => strtolower(trim($email))
            ])
            ->getRow();
    }

}
