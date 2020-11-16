<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\DashboardModel;
use App\Models\ProfileModel;

class Dashboard extends BaseController
{

    protected $authenticationModel, $profileModel, $dashboardModel;

    function __construct()
    {
        helper('url');
        $this->authenticationModel = new AuthenticationModel();
        $this->profileModel = new ProfileModel($this->authenticationModel);
        $this->dashboardModel = new DashboardModel($this->authenticationModel);
    }

    // GET
    function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/dashboard', [
            'session' => $session,
            'title' => 'Dashboard',
            'semesterCount' => $this->dashboardModel->getSemesterCount()->total,
            'studyProgramCount' => $this->dashboardModel->getStudyProgramCount()->total,
            'studyProgramAdminCount' => $this->dashboardModel->getStudyProgramAdminCount()->total,
            'classCount' => $this->dashboardModel->getClassCount()->total,
            'studentCount' => $this->dashboardModel->getStudentCount()->total,
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
    }
}