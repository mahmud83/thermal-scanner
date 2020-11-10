<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\ScheduleModel;
use DateTime;
use Exception;

class Schedule extends BaseController
{
    protected $authenticationModel, $scheduleModel;

    function __construct()
    {
        helper('url');
        $this->authenticationModel = new AuthenticationModel();
        $this->scheduleModel = new ScheduleModel();
    }

    // GET
    public function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/schedule', [
            'session' => $session,
            'title' => 'Manajemen Jadwal'
        ]);
    }

    // GET
    public function getScheduleList()
    {
        $sid = $this->request->getGet('sid');
        $searchTerm = $this->request->getGet('search');
        if (password_verify(session_id(), $sid)) {
            try {
                $data = $this->scheduleModel->getScheduleList($searchTerm);
                if ($data === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function addSchedule()
    {
        $sid = $this->request->getPost('sid');
        $classID = $this->request->getPost('class_id');
        $lecturerID = $this->request->getPost('lecturer_id');
        $name = $this->request->getPost('name');
        $dateStart = $this->request->getPost('date_start');
        $dateEnd = $this->request->getPost('date_end');
        if (password_verify(session_id(), $sid)) {
            if (empty($classID) || empty($lecturerID) || empty($name) || empty($dateStart) || empty($dateEnd)) return $this->response->setStatusCode(403);
            try {
                $dateStart = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateStart)->getTimestamp());
                $dateEnd = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateEnd)->getTimestamp());
                $insertedRow = $this->scheduleModel->addSchedule($classID, $lecturerID, $name, $dateStart, $dateEnd);
                if ($insertedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($insertedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function editSchedule()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        $classID = $this->request->getPost('class_id');
        $lecturerID = $this->request->getPost('lecturer_id');
        $name = $this->request->getPost('name');
        $dateStart = $this->request->getPost('date_start');
        $dateEnd = $this->request->getPost('date_end');
        if (password_verify(session_id(), $sid)) {
            if (empty($id) || empty($classID) || empty($lecturerID) || empty($name) || empty($dateStart) || empty($dateEnd)) return $this->response->setStatusCode(403);
            try {
                $dateStart = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateStart)->getTimestamp());
                $dateEnd = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateEnd)->getTimestamp());
                $updatedRow = $this->scheduleModel->editSchedule($id, $classID, $lecturerID, $name, $dateStart, $dateEnd);
                if ($updatedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($updatedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // DELETE
    public function deleteSchedule()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        if (password_verify(session_id(), $sid)) {
            if (empty($id)) return $this->response->setStatusCode(403);
            try {
                $deletedRow = $this->scheduleModel->deleteSchedule($id);
                if ($deletedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($deletedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function importSchedule()
    {
        $sid = $this->request->getPost('sid');
        $file = $this->request->getFile('file');
        if (password_verify(session_id(), $sid)) {
            if (empty($file)) return $this->response->setStatusCode(403);
            try {
                $fileType = IOFactory::identify($file);
                $reader = IOFactory::createReader($fileType);
                $sheet = $reader->load($file)->getActiveSheet();
                $checkTemplate1 = strtolower(trim($sheet->getCell('A2')->getValue())) == 'no.';
                $checkTemplate2 = strtolower(trim($sheet->getCell('B2')->getValue())) == 'kode jadwal';
                $checkTemplate3 = strtolower(trim($sheet->getCell('C2')->getValue())) == 'nama jadwal';
                $checkTemplate4 = strtolower(trim($sheet->getCell('D2')->getValue())) == 'kelas';
                $checkTemplate5 = strtolower(trim($sheet->getCell('E2')->getValue())) == 'prodi';
                $checkTemplate6 = strtolower(trim($sheet->getCell('F2')->getValue())) == 'dosen';
                $checkTemplate7 = strtolower(trim($sheet->getCell('G2')->getValue())) == 'waktu mulai';
                $checkTemplate8 = strtolower(trim($sheet->getCell('H2')->getValue())) == 'waktu selesai';
                $checkTemplate9 = strtolower(trim($sheet->getCell('I2')->getValue())) == 'kode absensi';
                $checkTemplate10 = strtolower(trim($sheet->getCell('J2')->getValue())) == 'tanggal ditambahkan';
                if ($checkTemplate1 && $checkTemplate2 && $checkTemplate3 && $checkTemplate4 &&
                    $checkTemplate5 && $checkTemplate6 && $checkTemplate7 && $checkTemplate8 &&
                    $checkTemplate9 && $checkTemplate10) $startRow = 3;
                else return $this->response->setStatusCode(403);
                $highestRow = $sheet->getHighestRow();
                $highestRow = count(array_filter(array_map('array_filter', $sheet->rangeToArray("B1:B$highestRow")))) + 1;
                $scheduleCodes = array();
                $names = array();
                $classNames = array();
                $studyProgramNames = array();
                $lecturerNames = array();
                $dateStarts = array();
                $dateEnds = array();
                $attendanceCodes = array();
                $createdOns = array();
                for ($i = $startRow; $i <= $highestRow; $i++) {
                    $scheduleCodes[] = trim($sheet->getCell("B$i")->getValue());
                    $names[] = trim($sheet->getCell("C$i")->getValue());
                    $classNames[] = trim($sheet->getCell("D$i")->getValue());
                    $studyProgramNames[] = trim($sheet->getCell("E$i")->getValue());
                    $lecturerNames[] = trim($sheet->getCell("F$i")->getValue());
                    $attendanceCodes[] = trim($sheet->getCell("I$i")->getValue());
                    $dateStart = trim($sheet->getCell("G$i")->getValue());
                    if (!empty($dateStart)) $dateStart = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i', $dateStart)->getTimestamp());
                    else $dateStart = null;
                    $dateStarts[] = $dateStart;
                    $dateEnd = trim($sheet->getCell("H$i")->getValue());
                    if (!empty($dateEnd)) $dateEnd = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i', $dateEnd)->getTimestamp());
                    else $dateEnd = null;
                    $dateEnds[] = $dateEnd;
                    $createdOn = trim($sheet->getCell("J$i")->getValue());
                    if (!empty($createdOn)) $createdOn = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i', $createdOn)->getTimestamp());
                    else $createdOn = null;
                    $createdOns[] = $createdOn;
                }
                $insertedRows = $this->scheduleModel->importSchedule($scheduleCodes, $classNames, $studyProgramNames, $lecturerNames, $names, $dateStarts, $dateEnds, $attendanceCodes, $createdOns);
                if ($insertedRows === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON(['success' => true]);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // DELETE
    public function truncateSchedule()
    {
        $sid = $this->request->getPost('sid');
        if (password_verify(session_id(), $sid)) {
            try {
                $result = $this->scheduleModel->truncateSchedule();
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function renewScheduleCode()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        if (password_verify(session_id(), $sid)) {
            if (empty($id)) return $this->response->setStatusCode(403);
            try {
                $schedule = $this->scheduleModel->renewScheduleCode($id);
                if ($schedule === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($insertedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }
}