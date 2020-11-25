<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class StudyProgramAdminModel extends Model
{

    protected $db, $profileModel;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->profileModel = new ProfileModel(new AuthenticationModel());
    }

    function getAdminList($searchTerm)
    {
        $this->db->transBegin();
        if (empty($searchTerm))
            $data = $this->db->table('study_program_admin')
                ->select('
                    user.id as user_id, user.email as user_email, user.name as user_name,
                    study_program.id as study_program_id, study_program.name as study_program_name,
                    study_program_admin.id as id, study_program_admin.created_on as created_on
                ')
                ->join('user', 'user.id = study_program_admin.user_id', 'left')
                ->join('study_program', 'study_program.id = study_program_admin.study_program_id', 'left')
                ->orderBy('study_program_admin.created_on', 'ASC')
                ->getWhere(['user.type' => 2])
                ->getResultArray();
        else
            $data = $this->db->table('study_program_admin')
                ->select('
                    user.id as user_id, user.email as user_email, user.name as user_name,
                    study_program.id as study_program_id, study_program.name as study_program_name,
                    study_program_admin.id as id, study_program_admin.created_on as created_on
                ')
                ->join('user', 'user.id = study_program_admin.user_id', 'left')
                ->join('study_program', 'study_program.id = study_program_admin.study_program_id', 'left')
                ->like('lower(trim(user.name))', strtolower(trim($searchTerm)))
                ->like('lower(trim(user.email))', strtolower(trim($searchTerm)))
                ->like('lower(trim(study_program.name))', strtolower(trim($searchTerm)))
                ->like('date_format(study_program_admin.created_on, "%d/%m/%Y %h/%i %p")', strtolower(trim($searchTerm)))
                ->orderBy('study_program_admin.created_on', 'ASC')
                ->getWhere(['user.type' => 2])
                ->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addAdmin(int $studyProgramId, string $email, string $password, string $name)
    {
        $this->db->transBegin();
        $user = $this->profileModel->getProfile($email);
        if ($user != null) {
            $this->db->transRollback();
            return null;
        }
        $this->db->table('user')->insert([
            'user.email' => trim($email),
            'user.password' => password_hash($password, PASSWORD_DEFAULT),
            'user.name' => trim($name),
            'user.type' => 2
        ]);
        $this->db->table('study_program_admin')->insert([
            'study_program_admin.user_id' => $this->db->insertID(),
            'study_program_admin.study_program_id' => $studyProgramId
        ]);
        $insertedRow = $this->db->table('study_program_admin')
            ->select('
                    user.id as user_id, user.email as user_email, user.name as user_name,
                    study_program.id as study_program_id, study_program.name as study_program_name,
                    study_program_admin.id as id, study_program_admin.created_on as created_on
                ')
            ->join('user', 'user.id = study_program_admin.user_id', 'left')
            ->join('study_program', 'study_program.id = study_program_admin.study_program_id', 'left')
            ->getWhere(['study_program_admin.id' => $this->db->insertID()])
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editAdmin(int $id, int $studyProgramId, string $email, string $name)
    {
        $this->db->transBegin();
        $userId = $this->db->table('study_program_admin')->getWhere(['id' => $id])->getRow()->user_id;
        $user = $this->db->table('user')->getWhere(['id' => $userId])->getRow();
        if (!$this->profileModel->verifyEmail($user->id, $user->type, $email)) {
            $this->db->transRollback();
            return null;
        }
        $this->db->table('user')
            ->where([
                'user.id' => $userId
            ])->update([
                'user.email' => trim($email),
                'user.name' => trim($name)
            ]);
        $this->db->table('study_program_admin')
            ->where([
                'study_program_admin.id' => $id
            ])->update([
                'study_program_admin.study_program_id' => $studyProgramId
            ]);
        $updatedRow = $this->db->table('study_program_admin')
            ->select('
                user.id as user_id, user.email as user_email, user.name as user_name,
                study_program.id as study_program_id, study_program.name as study_program_name,
                study_program_admin.id as id, study_program_admin.created_on as created_on
            ')
            ->join('user', 'user.id = study_program_admin.user_id', 'left')
            ->join('study_program', 'study_program.id = study_program_admin.study_program_id', 'left')
            ->getWhere(['study_program_admin.id' => $id])
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function resetAdminPassword(int $id, string $password)
    {
        $this->db->transBegin();
        $this->db->table('user')
            ->where([
                'user.id' => $this->db->table('study_program_admin')->getWhere(['id' => $id])->getRow()->user_id
            ])->update([
                'user.password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
        $updatedRow = $this->db->table('study_program_admin')
            ->select('
                    user.id as user_id, user.email as user_email, user.name as user_name,
                    study_program.id as study_program_id, study_program.name as study_program_name,
                    study_program_admin.id as id, study_program_admin.created_on as created_on
                ')
            ->join('user', 'user.id = study_program_admin.user_id', 'left')
            ->join('study_program', 'study_program.id = study_program_admin.study_program_id', 'left')
            ->getWhere(['study_program_admin.id' => $id])
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteAdmin(int $id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db->table('study_program_admin')
            ->select('
                    user.id as user_id, user.email as user_email, user.name as user_name,
                    study_program.id as study_program_id, study_program.name as study_program_name,
                    study_program_admin.id as id, study_program_admin.created_on as created_on
                ')
            ->join('user', 'user.id = study_program_admin.user_id', 'left')
            ->join('study_program', 'study_program.id = study_program_admin.study_program_id', 'left')
            ->getWhere(['study_program_admin.id' => $id])
            ->getRow();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('study_program_admin')->where(['study_program_admin.id' => $id])->delete();
        $this->db->table('user')->where(['user.id' => $deletedRow->user_id])->delete();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function truncateAdmin()
    {
        $this->db->transBegin();
        $userIds = $this->db->table('study_program_admin')
            ->select('study_program_admin.user_id')
            ->get()
            ->getResultArray();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($userIds as $userId) $this->db->table('user')->where(['user.id' => $userId])->delete();
        $this->db->table('study_program_admin')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }
}
