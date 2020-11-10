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
        if(empty($userStudyProgramId))
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
        if(empty($userStudyProgramId))
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
        if(empty($userStudyProgramId))
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
        if(empty($userStudyProgramId))
            $data = $this->db
                ->table('student_attendance')
                ->select('
                    student_attendance.id as id, student_attendance.name as name, student_attendance.nim as nim,
                    student_attendance.profile_picture as profile_picture, student_attendance.created_on as created_on,
                    schedule.id as schedule_id, schedule.name as schedule_name, class.id as class_id,
                    class.name as class_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('schedule', 'schedule.id = student_attendance.schedule_id', 'left')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
                ->orderBy('student_attendance.created_on', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        else
            $data = $this->db
                ->table('student_attendance')
                ->select('
                    student_attendance.id as id, student_attendance.name as name, student_attendance.nim as nim,
                    student_attendance.profile_picture as profile_picture, student_attendance.created_on as created_on,
                    schedule.id as schedule_id, schedule.name as schedule_name, class.id as class_id,
                    class.name as class_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('schedule', 'schedule.id = student_attendance.schedule_id', 'left')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
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
        if(empty($userStudyProgramId))
            $data = $this->db
                ->table('schedule')
                ->select('class.name as class_name, count(schedule.id) as schedule_count')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->groupBy('class_name')
                ->orderBy('schedule_count', 'desc')
                ->limit(5)
                ->get()
                ->getResultArray();
        else
            $data = $this->db
                ->table('schedule')
                ->select('class.name as class_name, count(schedule.id) as schedule_count')
                ->join('class', 'class.id = schedule.class_id', 'left')
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
                if(empty($userStudyProgramId))
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
                if(empty($userStudyProgramId))
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