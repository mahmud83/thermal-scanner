<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;
use DateInterval;
use DateTime;
use Exception;

class DashboardModel extends Model
{
    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    function getDetectionCount()
    {
        $this->db->transBegin();
        $data = $this->db->table('detection_history')
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

    function getNormalTempCount()
    {
        $this->db->transBegin();
        $data = $this->db->table('detection_history')
            ->select('count(*) as total')
            ->where('temperature <= 38')
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

    function getHighTempCount()
    {
        $this->db->transBegin();
        $data = $this->db->table('detection_history')
            ->select('count(*) as total')
            ->where('temperature > 38')
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

    function getNewestDetectionList()
    {
        $this->db->transBegin();
        $data = $this->db
            ->table('detection_history')
            ->orderBy('detection_history.created_on', 'DESC')
            ->limit(5)
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

    function getHighTempAvgList()
    {
        $this->db->transBegin();
        $data = $this->db
            ->table('detection_history')
            ->select('class.name as class_name, count(schedule_history.id) as schedule_count')
            ->groupBy('class_name')
            ->orderBy('schedule_count', 'desc')
            ->limit(5)
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

    function getAttendanceGraphicData($pivotDate, $pastCount)
    {
        try {
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

    function getScheduleGraphicData($pivotDate, $pastCount)
    {
        try {
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

    function getPastDayList($pivotDate, $pastCount)
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

    function getPastMonthList($pivotDate, $pastCount)
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