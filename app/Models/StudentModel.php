<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class StudentModel extends Model
{

    protected $db, $authenticationModel;

    function __construct(AuthenticationModel $authenticationModel)
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->authenticationModel = $authenticationModel;
    }

    function getStudentList($searchTerm)
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        if (empty($searchTerm))
            $builder = $this->db->table('student')
                ->select('
                    student.id as id, student.name as name, student.nim as nim, student.created_on as created_on,
                    class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                    study_program.name as study_program_name, semester.id as semester_id, semester.name as semester_name
                ')
                ->join('class', 'class.id = student.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = student.semester_id', 'left')
                ->orderBy('student.created_on', 'ASC');
        else
            $builder = $this->db->table('student')
                ->select('
                    student.id as id, student.name as name, student.nim as nim, student.created_on as created_on,
                    class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                    study_program.name as study_program_name, semester.id as semester_id, semester.name as semester_name
                ')
                ->join('class', 'class.id = student.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = student.semester_id', 'left')
                ->like('lower(trim(student.name))', strtolower(trim($searchTerm)))
                ->like('lower(trim(class.name))', strtolower(trim($searchTerm)))
                ->like('lower(trim(study_program.name))', strtolower(trim($searchTerm)))
                ->like('lower(trim(semester.name))', strtolower(trim($searchTerm)))
                ->orderBy('student.created_on', 'ASC');
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

    function getFilteredStudentList($classIds, $semesterIds)
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        $builder = $this->db
            ->table('student')
            ->select('
                    student.id as id, student.name as name, student.nim as nim, student.created_on as created_on,
                    class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                    study_program.name as study_program_name, semester.id as semester_id, semester.name as semester_name
                ')
            ->join('class', 'class.id = student.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = student.semester_id', 'left')
            ->orderBy('student.created_on', 'ASC');
        if (!empty($classIds)) foreach ($classIds as $classId) $builder->like('class.id', $classId);
        if (!empty($semesterIds)) foreach ($semesterIds as $semesterId) $builder->like('semester.id', $semesterId);
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

    function addStudent(int $classId, int $semesterId, string $nim, string $name)
    {
        $this->db->transBegin();
        $this->db->table('student')->insert([
            'student.class_id' => $classId,
            'student.semester_id' => $semesterId,
            'student.nim' => (string)$nim,
            'student.name' => trim($name)
        ]);
        $insertedRow = $this->db->table('student')
            ->select('
                student.id as id, student.name as name, student.nim as nim, student.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id, semester.name as semester_name
            ')
            ->join('class', 'class.id = student.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = student.semester_id', 'left')
            ->getWhere([
                'student.id' => $this->db->insertID()
            ])
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addBatchStudent(
        array $classNames, array $studyProgramNames, array $semesterNames, array $nims,
        array $names, array $createdOns
    )
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        for ($i = 0; $i < count($names); $i++) {
            $studyProgramId = $this->db->table('study_program')->select('study_program.id as id')->getWhere([
                'lower(trim(study_program.name))' => strtolower(trim($studyProgramNames[$i]))
            ])->getRow()->id;
            if(empty($userStudyProgramId)) $userStudyProgramId = $studyProgramId;
            $data = [
                'student.class_id' => $this->db->table('class')->getWhere([
                    'lower(trim(class.name))' => strtolower(trim($classNames[$i])),
                    'class.study_program_id' => $studyProgramId
                ])->getRow()->id,
                'student.semester_id' => $this->db->table('semester')->getWhere(['lower(trim(semester.name))' => strtolower(trim($semesterNames[$i]))])->getRow()->id,
                'student.nim' => trim($nims[$i]),
                'student.name' => trim($names[$i]),
            ];
            if (!empty($createdOns[$i])) $data['student.created_on'] = trim($createdOns[$i]);
            if (!empty($data['student.class_id']) && !empty($data['student.semester_id']) &&
                !empty($data['student.nim']) && !empty($data['student.name']) &&
                $studyProgramId == $userStudyProgramId)
                $this->db->table('student')->insert($data);
        }
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function editStudent(int $id, int $classId, int $semesterId, string $nim, string $name)
    {
        $this->db->transBegin();
        $this->db->table('student')->where(['student.id' => $id])->update([
            'student.class_id' => $classId,
            'student.semester_id' => $semesterId,
            'student.nip' => (string)$nim,
            'student.name' => trim($name)
        ]);
        $updatedRow = $this->db->table('student')
            ->select('
                student.id as id, student.name as name, student.nim as nim, student.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id, semester.name as semester_name
            ')
            ->join('class', 'class.id = student.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = student.semester_id', 'left')
            ->getWhere([
                'student.id' => $id
            ])
            ->getRow();
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
        $deletedRow = $this->db->table('student')
            ->select('
                student.id as id, student.name as name, student.nim as nim, student.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id, semester.name as semester_name
            ')
            ->join('class', 'class.id = student.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = student.semester_id', 'left')
            ->getWhere([
                'student.id' => $id
            ])
            ->getRow();
        $this->db->table('student')->where(['student.id' => $id])->delete();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importStudent(
        array $classNames, array $studyProgramNames, array $semesterNames, array $nims,
        array $names, array $createdOns
    )
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('student')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $studyProgramId = $this->db->table('study_program')->select('study_program.id as id')->getWhere([
                'lower(trim(study_program.name))' => strtolower(trim($studyProgramNames[$i]))
            ])->getRow()->id;
            if(empty($userStudyProgramId)) $userStudyProgramId = $studyProgramId;
            $data = [
                'student.class_id' => $this->db->table('class')->getWhere([
                    'lower(trim(class.name))' => strtolower(trim($classNames[$i])),
                    'class.study_program_id' => $studyProgramId
                ])->getRow()->id,
                'student.semester_id' => $this->db->table('semester')->getWhere(['lower(trim(semester.name))' => strtolower(trim($semesterNames[$i]))])->getRow()->id,
                'student.nim' => trim($nims[$i]),
                'student.name' => trim($names[$i]),
            ];
            if (!empty($createdOns[$i])) $data['student.created_on'] = trim($createdOns[$i]);
            if (!empty($data['student.class_id']) && !empty($data['student.semester_id']) &&
                !empty($data['student.nim']) && !empty($data['student.name']) &&
                $studyProgramId == $userStudyProgramId)
                $this->db->table('student')->insert($data);
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
