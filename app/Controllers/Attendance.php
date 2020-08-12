<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\AttendanceModel;

class Attendance extends BaseController
{
    protected $authenticationModel, $attendanceModel;

    function __construct()
    {
		helper('url');
		$this->authenticationModel = new AuthenticationModel();
		$this->attendanceModel = new AttendanceModel();
    }

	public function index()
	{
		if($this->authenticationModel->getSession()) return view('pages/attendance', ['title' => 'Riwayat Kehadiran']);
		else redirect('signin');
	}

	public function getAttendanceList()
	{
		$sid = $this->request->getGet('sid');
		if(password_verify(session_id(), $sid)) {
			try {
				$data = $this->attendanceModel->getAttendanceList();
				if($data === null) return $this->response->setStatusCode(500);
				else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}
}