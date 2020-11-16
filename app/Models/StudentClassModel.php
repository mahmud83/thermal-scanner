<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class StudentClassModel extends Model
{

    protected $db, $authenticationModel;

    function __construct(AuthenticationModel $authenticationModel)
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->authenticationModel = $authenticationModel;
    }

    function getClassList($searchTerm)
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        if (empty($searchTerm))
            $builder = $this->db->table('class')
                ->select('class.*, study_program.name as study_program_name')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->orderBy('class.created_on', 'ASC');
        else
            $builder = $this->db->table('class')
                ->select('class.*, study_program.name as study_program_name')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->like(['lower(trim(class.name))' => strtolower(trim($searchTerm))])
                ->like(['lower(trim(study_program.name))' => strtolower(trim($searchTerm))])
                ->like('date_format(class.created_on, "%d/%m/%Y %h/%i %p")', strtolower(trim($searchTerm)))
                ->orderBy('class.created_on', 'ASC');
        if (!empty($userStudyProgramId)) $builder->where(['study_program.id' => $userStudyProgramId]);
        $data = $builder->get()->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addClass(int $studyProgramId, string $name)
    {
        $this->db->transBegin();
        $this->db->table('class')->insert([
            'class.study_program_id' => $studyProgramId,
            'class.name' => trim($name)
        ]);
        $insertedRow = $this->db->table('class')
            ->select('class.*, study_program.name as study_program_name')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->getWhere(['class.id' => $this->db->insertID()])
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editClass(int $id, int $studyProgramId, string $name)
    {
        $this->db->transBegin();
        $this->db->table('class')->where(['class.id' => $id])->update([
            'class.study_program_id' => $studyProgramId,
            'class.name' => trim($name)
        ]);
        $updatedRow = $this->db->table('class')
            ->select('class.*, study_program.name as study_program_name')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->getWhere(['class.id' => $id])
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteClass(int $id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db->table('class')
            ->select('class.*, study_program.name as study_program_name')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->getWhere(['class.id' => $id])
            ->getRow();
        $this->db->table('class')->where(['class.id' => $id])->delete();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importClass(array $studyPrograms, array $names, array $createdOns)
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        $truncable = $this->db->query('select count(*) as total from class right join schedule on schedule.class_id = class.id')->getRow()->total;
        if ($truncable > 0) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('class')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $data = [
                'class.study_program_id' => $this->db->table('study_program')->getWhere(['lower(trim(study_program.name))' => strtolower(trim($studyPrograms[$i]))])->getRow()->id,
                'class.name' => trim($names[$i])
            ];
            if (!empty($createdOns[$i])) $data['class.created_on'] = trim($createdOns[$i]);
            if (!empty($data['class.study_program_id']) && !empty($data['class.name']) &&
                $data['class.study_program_id'] == $userStudyProgramId)
                $this->db->table('class')->insert($data);
        }
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function truncateClass()
    {
        $this->db->transBegin();
        $truncable = $this->db->query('select count(*) as total from class right join schedule on schedule.class_id = class.id')->getRow()->total;
        if ($truncable > 0) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('class')->truncate();
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