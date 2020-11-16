<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class SemesterModel extends Model
{
    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    function getSemesterList($searchTerm)
    {
        $this->db->transBegin();
        if (empty($searchTerm))
            $data = $this->db->table('semester')->orderBy('semester.created_on', 'ASC')->get()->getResultArray();
        else
            $data = $this->db->table('semester')->orderBy('semester.created_on', 'ASC')
                ->like('lower(trim(semester.name))', strtolower(trim($searchTerm)))
                ->like('date_format(semester.created_on, "%d/%m/%Y %h/%i %p")', strtolower(trim($searchTerm)))
                ->get()
                ->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addSemester(string $name)
    {
        $this->db->transBegin();
        $this->db->table('semester')->insert([
            'semester.name' => trim($name)
        ]);
        $insertedRow = $this->db->table('semester')->getWhere(['semester.id' => $this->db->insertID()])->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editSemester(int $id, string $name)
    {
        $this->db->transBegin();
        $this->db->table('semester')->where(['semester.id' => $id])->update([
            'semester.name' => trim($name)
        ]);
        $updatedRow = $this->db->table('semester')->getWhere(['semester.id' => $id])->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteSemester(int $id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db->table('semester')->getWhere(['semester.id' => $id])->getRow();
        $this->db->table('semester')->where(['semester.id' => $id])->delete();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importSemester(array $names, array $createdOns)
    {
        $this->db->transBegin();
        $truncable = $this->db->query('select count(*) as total from semester right join schedule on schedule.semester_id = semester.id')->getRow()->total;
        $truncable += $this->db->query('select count(*) as total from semester right join student on student.semester_id = semester.id')->getRow()->total;
        if ($truncable > 0) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('semester')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $data = [
                'semester.name' => trim($names[$i])
            ];
            if (!empty($createdOns[$i])) $data['semester.created_on'] = trim($createdOns[$i]);
            if (!empty($data['semester.name']))
                $this->db->table('semester')->insert($data);
        }
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function truncateSemester()
    {
        $this->db->transBegin();
        $truncable = $this->db->query('select count(*) as total from semester right join schedule on schedule.semester_id = semester.id')->getRow()->total;
        $truncable += $this->db->query('select count(*) as total from semester right join student on student.semester_id = semester.id')->getRow()->total;
        if ($truncable > 0) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('semester')->truncate();
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
