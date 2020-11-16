<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class ScheduleModel extends Model
{
    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    function getScheduleList($searchTerm)
    {
        $this->db->transBegin();
        if (empty($searchTerm))
            $data = $this->db
                ->table('schedule')
                ->select('
                    schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                    schedule.date_start as date_start, schedule.date_end as date_end,
                    schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                    class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                    study_program.name as study_program_name, semester.id as semester_id,
                    semester.name as semester_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = schedule.semester_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
                ->orderBy('schedule.created_on', 'ASC')
                ->get()
                ->getResultArray();
        else
            $data = $this->db
                ->table('schedule')
                ->select('
                    schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                    schedule.date_start as date_start, schedule.date_end as date_end,
                    schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                    class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                    study_program.name as study_program_name, semester.id as semester_id,
                    semester.name as semester_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = schedule.semester_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
                ->orderBy('schedule.created_on', 'ASC')
                ->like('lower(trim(schedule.name))', strtolower(trim($searchTerm)))
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

    function addSchedule(int $classId, int $semesterId, int $lecturerId, string $name, string $dateStart, string $dateEnd)
    {
        $this->db->transBegin();
        $this->db->table('schedule')->insert([
            'schedule.class_id' => $classId,
            'schedule.semester_id' => $semesterId,
            'schedule.lecturer_id' => $lecturerId,
            'schedule.schedule_code' => $this->generateScheduleCode($classId, $lecturerId),
            'schedule.name' => trim($name),
            'schedule.date_start' => trim($dateStart),
            'schedule.date_end' => trim($dateEnd),
            'schedule.attendance_code' => $this->generateAttendanceCode()
        ]);
        $insertedRow = $this->db
            ->table('schedule')
            ->select('
                schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                schedule.date_start as date_start, schedule.date_end as date_end,
                schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id,
                semester.name as semester_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
            ')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = schedule.semester_id', 'left')
            ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
            ->getWhere(['schedule.id' => $this->db->insertId()])
            ->getRow();
        if ($this->db->transStatus() && !empty($insertedRow)) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editSchedule(int $id, int $classId, int $semesterId, int $lecturerId, string $name, string $dateStart, string $dateEnd)
    {
        $this->db->transBegin();
        $this->db->table('schedule')->where(['schedule.id' => $id])->update([
            'schedule.class_id' => $classId,
            'schedule.semester_id' => $semesterId,
            'schedule.lecturer_id' => $lecturerId,
            'schedule.name' => trim($name),
            'schedule.date_start' => trim($dateStart),
            'schedule.date_end' => trim($dateEnd)
        ]);
        $updatedRow = $this->db
            ->table('schedule')
            ->select('
                schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                schedule.date_start as date_start, schedule.date_end as date_end,
                schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id,
                semester.name as semester_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
            ')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = schedule.semester_id', 'left')
            ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
            ->getWhere(['schedule.id' => $id])
            ->getRow();
        if ($this->db->transStatus() && !empty($updatedRow)) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteSchedule(int $id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db
            ->table('schedule')
            ->select('
                schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                schedule.date_start as date_start, schedule.date_end as date_end,
                schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id,
                semester.name as semester_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
            ')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = schedule.semester_id', 'left')
            ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
            ->getWhere(['schedule.id' => $id])
            ->getRow();
        $this->db->table('schedule')->where(['schedule.id' => $id])->delete();
        if ($this->db->transStatus() && !empty($deletedRow)) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importSchedule(
        array $scheduleCodes, array $classNames, array $studyProgramNames, array $semesterNames, array $lecturerNames,
        array $names, array $dateStarts, array $dateEnds, array $student_attendanceCodes, array $createdOns
    )
    {
        $this->db->transBegin();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('schedule')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $data = [
                'schedule.schedule_code' => $scheduleCodes[$i],
                'schedule.class_id' => $this->db->table('class')->getWhere([
                    'lower(trim(class.name))' => strtolower(trim($classNames[$i])),
                    'class.study_program_id' => $this->db->table('study_program')->select('study_program.id as id')->getWhere([
                        'lower(trim(study_program.name))' => strtolower(trim($studyProgramNames[$i]))
                    ])->getRow()->id
                ])->getRow()->id,
                'schedule.semester_id' => $this->db->table('semester')->getWhere(['lower(trim(semester.name))' => strtolower(trim($semesterNames[$i]))])->getRow()->id,
                'schedule.lecturer_id' => $this->db->table('lecturer')->getWhere(['lower(trim(lecturer.name))' => strtolower(trim($lecturerNames[$i]))])->getRow()->id,
                'schedule.name' => trim($names[$i]),
                'schedule.date_start' => trim($dateStarts[$i]),
                'schedule.date_end' => trim($dateEnds[$i]),
                'schedule.attendance_code' => $student_attendanceCodes[$i]
            ];
            if (empty('schedule.class_id') || empty('schedule.lecturer_id')) {
                $this->db->transRollback();
                return null;
            }
            if (!empty($createdOns[$i])) $data['schedule.created_on'] = trim($createdOns[$i]);
            if (!empty($data['schedule.class_id']) && empty('schedule.semester_id') &&
                !empty($data['schedule.lecturer_id']) && !empty($data['schedule.name']) &&
                !empty($data['schedule.date_start']) && !empty($data['schedule.date_end'])) {
                $this->db->table('schedule')->insert($data);
                $insertedRow[] = $this->db
                    ->table('schedule')
                    ->select('
                        schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                        schedule.date_start as date_start, schedule.date_end as date_end,
                        schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                        class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                        study_program.name as study_program_name, semester.id as semester_id,
                        semester.name as semester_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
                    ')
                    ->join('class', 'class.id = schedule.class_id', 'left')
                    ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                    ->join('semester', 'semester.id = schedule.semester_id', 'left')
                    ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
                    ->getWhere(['schedule.id' => $this->db->insertId()])
                    ->getRow();
            }
        }
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function truncateSchedule()
    {
        $this->db->transBegin();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('schedule')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function renewScheduleCode(int $id)
    {
        $this->db->transBegin();
        $this->db->table('schedule')->where(['schedule.id' => $id])->update([
            'schedule.code' => $this->generateAttendanceCode()
        ]);
        $updatedRow = $this->db
            ->table('schedule')
            ->select('
                schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                schedule.date_start as date_start, schedule.date_end as date_end,
                schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id,
                semester.name as semester_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
            ')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = schedule.semester_id', 'left')
            ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
            ->getWhere(['schedule.id' => $id])
            ->getRow();
        if ($this->db->transStatus() && !empty($updatedRow)) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    private function generateScheduleCode(int $classId, int $lecturerId)
    {
        $scheduleCode = "S$classId$lecturerId";
        $scheduleCode .= substr(time(), 5);
        return strtoupper(trim($scheduleCode));
    }

    private function generateAttendanceCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $student_attendanceCode = '';
        for ($i = 0; $i < 6; $i++) $student_attendanceCode .= $characters[rand(0, strlen($characters) - 1)];
        return strtoupper(trim($student_attendanceCode));
    }
}
