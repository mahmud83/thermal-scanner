<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class StudentAttendanceModel extends Model
{

    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    function getAttendanceList()
    {
        $this->db->transBegin();
        $data = $this->db
            ->table('student_attendance')
            ->select('
                student_attendance.id as id, student_attendance.name as name, student_attendance.nim as nim,
                student_attendance.profile_picture as profile_picture, student_attendance.created_on as created_on,
                schedule.id as schedule_id, schedule.name as schedule_name, class.id as class_id,
                class.name as class_name, study_program.id as atudy_program_id,
                study_program.name as study_program_name, lecturer.id as lecturer_id, lecturer.name as lecturer_name
            ')
            ->join('schedule', 'schedule.id = student_attendance.schedule_id', 'left')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('lecturer', 'lecturer.id = schedule.lecturer_id', 'left')
            ->orderBy('student_attendance.created_on', 'ASC')
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
}