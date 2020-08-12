<?php namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $db;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    function getAttendanceList()
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
                ->orderBy('attendance_history.created_on', 'ASC')
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
}