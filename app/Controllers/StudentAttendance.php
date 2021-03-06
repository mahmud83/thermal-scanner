<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\StudentAttendanceModel;
use Exception;

class StudentAttendance extends BaseController
{
    protected $authenticationModel, $attendanceModel;

    function __construct()
    {
        helper('url');
        $this->authenticationModel = new AuthenticationModel();
        $this->attendanceModel = new StudentAttendanceModel();
    }

    // GET
    public function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/student_attendance', [
            'session' => $session,
            'title' => 'Riwayat Kehadiran Siswa'
        ]);
    }

    // GET
    public function getAttendanceList()
    {
        $sid = $this->request->getGet('sid');
        if (password_verify(session_id(), $sid)) {
            try {
                $data = $this->attendanceModel->getAttendanceList();
                if ($data === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setBody($e);
            }
        } else return $this->response->setStatusCode(401);
    }
}