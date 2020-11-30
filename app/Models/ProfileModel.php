<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class ProfileModel extends Model
{

    protected $db, $authenticationModel;

    function __construct(AuthenticationModel $authenticationModel)
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->authenticationModel = $authenticationModel;
    }

    function getProfile($email)
    {
        $this->db->transBegin();
        $data = $this->db->table('user')
            ->select('user.id, user.type, user.email, user.name, user.password, user.created_on')
            ->getWhere(['lower(trim(user.email))' => strtolower(trim($email))])
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editProfile(int $userId, int $userType, string $name, string $email, $password)
    {
        $this->db->transBegin();
        $newProfile = ['user.email' => trim($email), 'user.name' => trim($name)];
        if (!empty($password)) $newProfile['user.password'] = password_hash($password, PASSWORD_DEFAULT);
        $createdOn = $this->db->table('user')->getWhere(['user.id' => $userId])->getRow()->created_on;
        $this->db->table('user')->where(['user.id' => $userId])->update($newProfile);
        $this->authenticationModel->createSession($name, $email, $userType, $createdOn);
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function verifyEmail(int $userId, int $userType, string $email)
    {
        $this->db->transBegin();
        $verifyEmail = $this->db
            ->query("select * from user where user.id != $userId and user.type = $userType and user.email = '$email'")
            ->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return empty($verifyEmail);
        } else {
            $this->db->transRollback();
            return false;
        }
    }
}
