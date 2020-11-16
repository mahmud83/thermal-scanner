<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class StudentModel extends Model
{
    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    function getStudentList($searchTerm)
    {
        $this->db->transBegin();
        if (empty($searchTerm))
            $data = $this->db->table('student')->orderBy('student.created_on', 'ASC')->get()->getResultArray();
        else
            $data = $this->db->table('student')->orderBy('student.created_on', 'ASC')
                ->like('lower(trim(student.name))', strtolower(trim($searchTerm)))->get()->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addStudent(int $semesterId, int $nim, string $name)
    {
        $this->db->transBegin();
        $this->db->table('student')->insert([
            'student.semester_id' => $semesterId,
            'student.nim' => (string)$nim,
            'student.name' => trim($name)
        ]);
        $insertedRow = $this->db->table('student')->getWhere(['student.id' => $this->db->insertID()])->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editStudent(int $id, int $semesterId, int $nim, string $name)
    {
        $this->db->transBegin();
        $this->db->table('student')->where(['student.id' => $id])->update([
            'student.semester_id' => $semesterId,
            'student.nip' => (string)$nim,
            'student.name' => trim($name)
        ]);
        $updatedRow = $this->db->table('student')->getWhere(['student.id' => $id])->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteStudent(int $id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db->table('student')->getWhere(['student.id' => $id])->getRow();
        $this->db->table('student')->where(['student.id' => $id])->delete();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importStudent(array $semesterNames, array $nims, array $names, array $createdOns)
    {
        $this->db->transBegin();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('student')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $data = [
                'student.semester_id' => $this->db->table('semester')->getWhere(['lower(trim(semester.name))' => strtolower(trim($semesterNames[$i]))])->getRow()->id,
                'student.nip' => trim($nims[$i]),
                'student.name' => trim($names[$i]),
            ];
            if (!empty($createdOns[$i])) $data['student.created_on'] = trim($createdOns[$i]);
            if (!empty($data['student.nip']) && !empty($data['student.name'])) $this->db->table('student')->insert($data);
        }
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function truncateStudent()
    {
        $this->db->transBegin();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('student')->truncate();
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
