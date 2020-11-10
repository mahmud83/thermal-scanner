<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class StudyProgramModel extends Model
{
    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    function getStudyProgramList($searchTerm)
    {
        $this->db->transBegin();
        if (empty($searchTerm))
            $data = $this->db->table('study_program')->orderBy('study_program.created_on', 'ASC')->get()->getResultArray();
        else
            $data = $this->db->table('study_program')->orderBy('study_program.created_on', 'ASC')
                ->like('lower(trim(study_program.name))', strtolower(trim($searchTerm)))->get()->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addStudyProgram(string $name)
    {
        $this->db->transBegin();
        $this->db->table('study_program')->insert(['study_program.name' => trim($name)]);
        $insertedRow = $this->db->table('study_program')->getWhere(['study_program.id' => $this->db->insertID()])->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editStudyProgram(int $id, string $name)
    {
        $this->db->transBegin();
        $this->db->table('study_program')->where(['study_program.id' => $id])->update([
            'study_program.name' => trim($name)
        ]);
        $updatedRow = $this->db->table('study_program')->getWhere(['study_program.id' => $id])->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteStudyProgram(int $id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db->table('study_program')->getWhere(['study_program.id' => $id])->getRow();
        $this->db->table('study_program')->where(['study_program.id' => $id])->delete();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importStudyProgram(array $names, array $createdOns)
    {
        $this->db->transBegin();
        $truncable = $this->db->query('select count(*) as total from study_program right join class on class.study_program_id = study_program.id')->getRow()->total;
        if ($truncable > 0) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('study_program')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $data = ['study_program.name' => trim($names[$i])];
            if (!empty($createdOns[$i])) $data['study_program.created_on'] = trim($createdOns[$i]);
            if (!empty($data['study_program.name'])) $this->db->table('study_program')->insert($data);
        }
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function truncateStudyProgram()
    {
        $this->db->transBegin();
        $truncable = $this->db->query('select count(*) as total from study_program right join class on class.study_program_id = study_program.id')->getRow()->total;
        if ($truncable > 0) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('study_program')->truncate();
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
