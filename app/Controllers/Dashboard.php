<?php namespace App\Controllers;

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
	    $session = $this->authenticationModel->getSession();
		if($session->user_name) return view('pages/dashboard', [
		    'session' => $session,
			'title' => 'Dashboard',
			'detectionCount' => $this->dashboardModel->getDetectionCount()->total,
            'normalTempCount' => $this->dashboardModel->getNormalTempCount()->total,
            'highTempCount' => $this->dashboardModel->getHighTempCount()->total,
			'newestDetectionList' => $this->dashboardModel->getNewestDetectionList(),
            'highTempAvgList' => [],
			'detectionGraphicData' => [
				'dayList' => $this->dashboardModel->getPastDayList(date('Y/m/d'), 7),
				'data' => []
			],
			'highTempGraphicData' => [
				'monthList' => $this->dashboardModel->getPastMonthList(date('Y/m/d'), 6),
				'data' => []
			]
		]);
		else return redirect('signin');
	}
}