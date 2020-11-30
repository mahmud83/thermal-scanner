<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;
use Config\Services;

class AuthenticationModel extends Model
{

    protected $db, $session;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->session = Services::session();
    }

    public function getSession()
    {
        return $this->session;
    }

    public function createSession($name, $email, $userType)
    {
        $this->session->set(['user_name' => $name, 'user_email' => $email, 'user_type' => $userType]);
    }

    public function destroySession()
    {
        $this->session->destroy();
    }

    public function signIn($email, $preferredUserType)
    {
        $this->db->transBegin();
        $data = $this->db->table('user')
            ->select(['user.type', 'user.email', 'user.password', 'user.name'])
            ->getWhere([
                'user.type' => $preferredUserType,
                'lower(trim(user.email))' => strtolower(trim($email))
            ])
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    public function resetAck()
    {
        $this->db->transBegin();
        $this->db->table('system_ack_log')->truncate();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    public function getAck()
    {
        $this->db->transBegin();
        $data = $this->db->table('system_ack_log')
            ->orderBy('created_on', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    public function sendAck(string $ip)
    {
        $this->db->transBegin();
        $this->db->table('system_ack_log')->insert(['ip' => $ip]);
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

}
