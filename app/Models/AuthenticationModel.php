<?php namespace App\Models;

use CodeIgniter\Model;

class AuthenticationModel extends Model
{

    protected $db, $session;

    function __construct()
    {
        $this->db = \Config\Database::connect();
		$this->session = \Config\Services::session();
    }

    public function getSession()
    {
        return $this->session->user;
    }

    public function createSession($email)
    {
        return $this->session->set('user', $email);
    }

    public function destroySession()
    {
        return $this->session->destroy();
    }

    public function signIn($email)
    {
        return $this->db->table('user')
                ->select(['user.email', 'user.password'])
                ->getWhere([
                    'user.role_id' => 1,
                    'lower(trim(user.email))' => strtolower(trim($email))
                ])
                ->getRow();
    }

}
