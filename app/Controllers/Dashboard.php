<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AuthenticationModel;
use App\Models\DashboardModel;

class Dashboard extends BaseController
{

    protected $authenticationModel, $dashboardModel;

    function __construct()
    {
		helper('url');
		$this->authenticationModel = new AuthenticationModel();
		$this->dashboardModel = new DashboardModel();
    }

	function index()
	{
		if($this->authenticationModel->getSession()) return view('pages/dashboard', [
			'title' => 'Dashboard',
			'classCount' => $this->dashboardModel->getClassCount()->total,
			'lecturerCount' => $this->dashboardModel->getLecturerCount()->total,
			'scheduleCount' => $this->dashboardModel->getScheduleCount()->total,
			'attendanceCount' => $this->dashboardModel->getAttendanceCount()->total,
			'newestAttendanceList' => $this->dashboardModel->getNewestAttendanceList(),
			'classScheduleList' => $this->dashboardModel->getClassScheduleList(),
			'attendanceGraphicData' => [
				'dayList' => $this->dashboardModel->getPastDayList(date('Y/m/d'), 7),
				'data' => $this->dashboardModel->getAttendanceGraphicData(date('Y/m/d'), 7)
			],
			'scheduleGraphicData' => [
				'monthList' => $this->dashboardModel->getPastMonthList(date('Y/m/d'), 6),
				'data' => $this->dashboardModel->getScheduleGraphicData(date('Y/m/d'), 6)
			]
		]);
		else return redirect('signin');
	}
}