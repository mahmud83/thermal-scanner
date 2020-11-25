<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;
use DateInterval;
use DateTime;
use Exception;

class DashboardModel extends Model
{

    protected $db, $authenticationModel;

    function __construct(AuthenticationModel $authenticationModel)
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->authenticationModel = $authenticationModel;
    }

    function getSemesterCount()
    {
        $this->db->transBegin();
        $data = $this->db->table('semester')
            ->select('count(*) as total')
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

    function getStudyProgramCount()
    {
        $this->db->transBegin();
        $data = $this->db->table('study_program')
            ->select('count(*) as total')
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

    function getStudyProgramAdminCount()
    {
        $this->db->transBegin();
        $data = $this->db->table('study_program_admin')
            ->select('count(*) as total')
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

    function getClassCount()
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        if (empty($userStudyProgramId))
            $data = $this->db->table('class')
                ->select('count(*) as total')
                ->get()
                ->getRow();
        else
            $data = $this->db->table('class')
                ->select('count(*) as total')
                ->getWhere(['class.study_program_id' => $userStudyProgramId])
                ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getStudentCount()
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        if (empty($userStudyProgramId))
            $data = $this->db->table('student')
                ->select('count(*) as total')
                ->get()
                ->getRow();
        else
            $data = $this->db->table('student')
                ->select('count(*) as total')
                ->join('class', 'class.id = student.class_id', 'left')
                ->getWhere(['class.study_program_id' => $userStudyProgramId])
                ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getLecturerCount()
    {
        $this->db->transBegin();
        $data = $this->db->table('lecturer')
            ->select('count(*) as total')
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

    function getScheduleCount()
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        if (empty($userStudyProgramId))
            $data = $this->db->table('schedule')
                ->select('count(*) as total')
                ->get()
                ->getRow();
        else
            $data = $this->db->table('schedule')
                ->select('count(*) as total')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->getWhere(['class.study_program_id' => $userStudyProgramId])
                ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getAttendanceCount()
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        if (empty($userStudyProgramId))
            $data = $this->db->table('student_attendance')
                ->select('count(*) as total')
                ->get()
                ->getRow();
        else
            $data = $this->db->table('student_attendance')
                ->select('count(*) as total')
                ->join('schedule', 'schedule.id = student_attendance.schedule_id', 'left')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->getWhere(['class.study_program_id' => $userStudyProgramId])
                ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getNewestAttendanceList()
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        if (empty($userStudyProgramId))
            $data = $this->db
                ->table('student_attendance')
                ->select('
                    student_attendance.id as id, student_attendance.profile_picture as profile_picture,
                    student_attendance.created_on as created_on, student.name as student_name,
                    student.nim as student_nim, schedule.id as id, schedule.schedule_code as schedule_code,
                    schedule.name as name, schedule.date_start as date_start, schedule.date_end as date_end,
                    schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                    class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                    study_program.name as study_program_name, semester.id as semester_id,
                    semester.name as semester_name,
                    (
                        select group_concat(distinct lecturer_schedule.lecturer_id separator ", ")
                        from lecturer_schedule
                        where lecturer_schedule.schedule_id = schedule.id
                        group by lecturer_schedule.schedule_id
                    ) as lecturer_ids,
                    (
                        select group_concat(distinct lecturer.name separator ", ")
                        from lecturer_schedule
                        left join lecturer on lecturer.id = lecturer_schedule.lecturer_id
                        where lecturer_schedule.schedule_id = schedule.id
                        group by lecturer_schedule.schedule_id
                    ) as lecturer_names
                ')
                ->join('student', 'student.id = student_attendance.student_id', 'left')
                ->join('schedule', 'schedule.id = student_attendance.schedule_id', 'left')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = schedule.semester_id', 'left')
                ->orderBy('student_attendance.created_on', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        else
            $data = $this->db
                ->table('student_attendance')
                ->select('
                    student_attendance.id as id, student_attendance.profile_picture as profile_picture,
                    student_attendance.created_on as created_on, student.name as student_name,
                    student.nim as student_nim, schedule.id as id, schedule.schedule_code as schedule_code,
                    schedule.name as name, schedule.date_start as date_start, schedule.date_end as date_end,
                    schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                    class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                    study_program.name as study_program_name, semester.id as semester_id,
                    semester.name as semester_name,
                    (
                        select group_concat(distinct lecturer_schedule.lecturer_id separator ", ")
                        from lecturer_schedule
                        where lecturer_schedule.schedule_id = schedule.id
                        group by lecturer_schedule.schedule_id
                    ) as lecturer_ids,
                    (
                        select group_concat(distinct lecturer.name separator ", ")
                        from lecturer_schedule
                        left join lecturer on lecturer.id = lecturer_schedule.lecturer_id
                        where lecturer_schedule.schedule_id = schedule.id
                        group by lecturer_schedule.schedule_id
                    ) as lecturer_names
                ')
                ->join('student', 'student.id = student_attendance.student_id', 'left')
                ->join('schedule', 'schedule.id = student_attendance.schedule_id', 'left')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = schedule.semester_id', 'left')
                ->orderBy('student_attendance.created_on', 'DESC')
                ->limit(5)
                ->getWhere(['class.study_program_id' => $userStudyProgramId])
                ->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getClassScheduleList()
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        if (empty($userStudyProgramId))
            $data = $this->db
                ->table('schedule')
                ->select('
                    class.name as class_name, study_program.name as study_program_name, semester.id as semester_id,
                    semester.name as semester_name, count(schedule.id) as schedule_count
                ')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = schedule.semester_id', 'left')
                ->groupBy('class_name')
                ->orderBy('schedule_count', 'desc')
                ->limit(5)
                ->get()
                ->getResultArray();
        else
            $data = $this->db
                ->table('schedule')
                ->select('
                    class.name as class_name, study_program.name as study_program_name, semester.id as semester_id,
                    semester.name as semester_name, count(schedule.id) as schedule_count
                ')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = schedule.semester_id', 'left')
                ->groupBy('class_name')
                ->orderBy('schedule_count', 'desc')
                ->limit(5)
                ->getWhere(['class.study_program_id' => $userStudyProgramId])
                ->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getAttendanceGraphicData(string $pivotDate, int $pastCount)
    {
        try {
            $this->db->transBegin();
            $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
            $pivotDate = new DateTime($pivotDate);
            for ($i = 0; $i < $pastCount; $i++) {
                if (empty($userStudyProgramId))
                    $data[] = $this->db
                        ->table('student_attendance')
                        ->select('count(*) as total')
                        ->getWhere([
                            'date_format(student_attendance.created_on, "%Y/%m/%d")' => $pivotDate->format('Y/m/d')
                        ])
                        ->getRow()
                        ->total;
                else
                    $data[] = $this->db
                        ->table('student_attendance')
                        ->select('count(*) as total')
                        ->join('schedule', 'schedule.id = student_attendance.schedule_id', 'left')
                        ->join('class', 'class.id = schedule.class_id', 'left')
                        ->getWhere([
                            'class.study_program_id' => $userStudyProgramId,
                            'date_format(student_attendance.created_on, "%Y/%m/%d")' => $pivotDate->format('Y/m/d')
                        ])
                        ->getRow()
                        ->total;
                $pivotDate->sub(new DateInterval('P1D'));
            }
            $data = array_reverse($data);
            if ($this->db->transStatus()) {
                $this->db->transCommit();
                return $data;
            } else {
                $this->db->transRollback();
                return null;
            }
        } catch (Exception $e) {
            die($e);
        }
    }

    function getScheduleGraphicData(string $pivotDate, int $pastCount)
    {
        try {
            $this->db->transBegin();
            $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
            $pivotDate = new DateTime($pivotDate);
            for ($i = 0; $i < $pastCount; $i++) {
                if (empty($userStudyProgramId))
                    $data[] = $this->db
                        ->table('schedule')
                        ->select('count(*) as total')
                        ->getWhere([
                            'date_format(schedule.created_on, "%Y/%m")' => $pivotDate->format('Y/m')
                        ])
                        ->getRow()
                        ->total;
                else
                    $data[] = $this->db
                        ->table('schedule')
                        ->select('count(*) as total')
                        ->join('class', 'class.id = schedule.class_id', 'left')
                        ->getWhere([
                            'class.study_program_id' => $userStudyProgramId,
                            'date_format(schedule.created_on, "%Y/%m")' => $pivotDate->format('Y/m')
                        ])
                        ->getRow()
                        ->total;
                $pivotDate->sub(new DateInterval('P1M'));
            }
            $data = array_reverse($data);
            if ($this->db->transStatus()) {
                $this->db->transCommit();
                return $data;
            } else {
                $this->db->transRollback();
                return null;
            }
        } catch (Exception $e) {
            die($e);
        }
    }

    function getPastDayList(string $pivotDate, int $pastCount)
    {
        try {
            $pivotDate = new DateTime($pivotDate);
            for ($i = 0; $i < $pastCount; $i++) {
                $pastDayList[] = $pivotDate->format('D');
                $pivotDate->sub(new DateInterval('P1D'));
            }
            return array_reverse($pastDayList);
        } catch (Exception $e) {
            die($e);
        }
    }

    function getPastMonthList(string $pivotDate, int $pastCount)
    {
        try {
            $pivotDate = new DateTime($pivotDate);
            for ($i = 0; $i < $pastCount; $i++) {
                $pastMonthList[] = $pivotDate->format('M');
                $pivotDate->sub(new DateInterval('P1M'));
            }
            return array_reverse($pastMonthList);
        } catch (Exception $e) {
            die($e);
        }
    }
}