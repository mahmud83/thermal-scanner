<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\SemesterModel;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Semester extends BaseController
{

    protected $authenticationModel, $semesterModel;

    function __construct()
    {
        helper('url');
        $this->authenticationModel = new AuthenticationModel();
        $this->semesterModel = new SemesterModel();
    }

    // GET
    public function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/semester', [
            'session' => $session,
            'title' => 'Manajemen Semester'
        ]);
    }

    // GET
    public function getSemesterList()
    {
        $sid = $this->request->getGet('sid');
        $searchTerm = $this->request->getGet('search');
        if (password_verify(session_id(), $sid)) {
            try {
                $data = $this->semesterModel->getSemesterList($searchTerm);
                if ($data === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function addSemester()
    {
        $sid = $this->request->getPost('sid');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($name)) return $this->response->setStatusCode(403);
            try {
                $insertedRow = $this->semesterModel->addSemester($name);
                if ($insertedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($insertedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function editSemester()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($id) || empty($name)) return $this->response->setStatusCode(403);
            try {
                $updatedRow = $this->semesterModel->editSemester($id, $name);
                if ($updatedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($updatedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function deleteSemester()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        if (password_verify(session_id(), $sid)) {
            if (empty($id)) return $this->response->setStatusCode(403);
            try {
                $deletedRow = $this->semesterModel->deleteSemester($id);
                if ($deletedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($deletedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function importSemester()
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
                $checkTemplate2 = strtolower(trim($sheet->getCell('B2')->getValue())) == 'nama semester';
                $checkTemplate3 = strtolower(trim($sheet->getCell('C2')->getValue())) == 'tanggal ditambahkan';
                if ($checkTemplate1 && $checkTemplate2 && $checkTemplate3)
                    $startRow = 3;
                else return $this->response->setStatusCode(403);
                $highestRow = $sheet->getHighestRow();
                $highestRow = count(array_filter(array_map('array_filter', $sheet->rangeToArray("B1:B$highestRow")))) + 1;
                $names = array();
                $createdOns = array();
                for ($i = $startRow; $i <= $highestRow; $i++) {
                    if (empty(trim($sheet->getCell("B$i")->getValue()))) {
                        $i--;
                        continue;
                    }
                    $names[] = trim($sheet->getCell("B$i")->getValue());
                    $createdOn = trim($sheet->getCell("C$i")->getValue());
                    if (!empty($createdOn)) $createdOn = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i', $createdOn)->getTimestamp());
                    else $createdOn = null;
                    $createdOns[] = $createdOn;
                }
                $result = $this->semesterModel->importSemester($names, $createdOns);
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function truncateSemester()
    {
        $sid = $this->request->getPost('sid');
        if (password_verify(session_id(), $sid)) {
            try {
                $result = $this->semesterModel->truncateSemester();
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }
}