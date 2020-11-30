<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\DetectionHistoryModel;
use Exception;

class DetectionHistory extends BaseController
{
    protected $authenticationModel, $detectionHistoryModel;

    function __construct()
    {
        helper('url');
        $this->authenticationModel = new AuthenticationModel();
        $this->detectionHistoryModel = new DetectionHistoryModel();
    }

    // GET
    public function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/detection_history', [
            'session' => $session,
            'title' => 'Riwayat Deteksi Suhu'
        ]);
    }

    // GET
    public function getDetectionList()
    {
        $sid = $this->request->getGet('sid');
        if (password_verify(session_id(), $sid)) {
            try {
                $data = $this->detectionHistoryModel->getDetectionList();
                if ($data === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setBody($e);
            }
        } else return $this->response->setStatusCode(401);
    }
}