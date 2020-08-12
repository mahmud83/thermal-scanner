<?php namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use DateInterval;

class DashboardModel extends Model
{
    protected $db;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    function getClassCount()
    {
        $this->db->transBegin();
        $data = $this->db->table('class')->select('count(*) as total')->get()->getRow();
        if($this->db->transStatus()) {
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
        $data = $this->db->table('lecturer')->select('count(*) as total')->get()->getRow();
        if($this->db->transStatus()) {
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
        $data = $this->db->table('schedule_history')->select('count(*) as total')->get()->getRow();
        if($this->db->transStatus()) {
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
        $data = $this->db->table('attendance_history')->select('count(*) as total')->get()->getRow();
        if($this->db->transStatus()) {
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
        $data = $this->db
                ->table('attendance_history')
                ->select('
                    attendance_history.id as id, attendance_history.name as name, attendance_history.nim as nim,
                    attendance_history.profile_picture as profile_picture, attendance_history.created_on as created_on,
                    schedule_history.id as schedule_id, schedule_history.name as schedule_name, class.id as class_id,
                    class.name as class_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('schedule_history', 'schedule_history.id = attendance_history.schedule_id', 'left')
                ->join('class', 'class.id = schedule_history.class_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule_history.lecturer_id', 'left')
                ->orderBy('attendance_history.created_on', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        if($this->db->transStatus()) {
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
        $data = $this->db
                ->table('schedule_history')
                ->select('class.name as class_name, count(schedule_history.id) as schedule_count')
                ->join('class', 'class.id = schedule_history.class_id', 'left')
                ->groupBy('class_name')
                ->orderBy('schedule_count', 'desc')
                ->limit(5)
                ->get()
                ->getResultArray();
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getAttendanceGraphicData($pivotDate, $pastCount)
    {
        $this->db->transBegin();
        $pivotDate = new DateTime($pivotDate);
        for ($i = 0; $i < $pastCount; $i++) {
            $data[] = $this->db
                ->table('attendance_history')
                ->select('count(*) as total')
                ->getWhere([
                    'date_format(attendance_history.created_on, "%Y/%m/%d")' => $pivotDate->format('Y/m/d')
                ])
                ->getRow()
                ->total;
            $pivotDate->sub(new DateInterval('P1D'));
        }
        $data = array_reverse($data);
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getScheduleGraphicData($pivotDate, $pastCount)
    {
        $this->db->transBegin();
        $pivotDate = new DateTime($pivotDate);
        for ($i = 0; $i < $pastCount; $i++) {
            $data[] = $this->db
                ->table('schedule_history')
                ->select('count(*) as total')
                ->getWhere([
                    'date_format(schedule_history.created_on, "%Y/%m")' => $pivotDate->format('Y/m')
                ])
                ->getRow()
                ->total;
            $pivotDate->sub(new DateInterval('P1M'));
        }
        $data = array_reverse($data);
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getPastDayList($pivotDate, $pastCount)
    {
        $pivotDate = new DateTime($pivotDate);
        for ($i = 0; $i < $pastCount; $i++) {
            $pastDayList[] = $pivotDate->format('D');
            $pivotDate->sub(new DateInterval('P1D'));
        }
        return array_reverse($pastDayList);
    }

    function getPastMonthList($pivotDate, $pastCount)
    {
        $pivotDate = new DateTime($pivotDate);
        for ($i = 0; $i < $pastCount; $i++) {
            $pastMonthList[] = $pivotDate->format('M');
            $pivotDate->sub(new DateInterval('P1M'));
        }
        return array_reverse($pastMonthList);
    }
}