<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\studentModel;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Student extends BaseController
{

    protected $authenticationModel, $studentModel;

    function __construct()
    {
        helper('url');
        $this->authenticationModel = new AuthenticationModel();
        $this->studentModel = new StudentModel();
    }

    // GET
    public function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/student', [
            'session' => $session,
            'title' => 'Manajemen Siswa'
        ]);
    }

    // GET
    public function getStudentList()
    {
        $sid = $this->request->getGet('sid');
        $searchTerm = $this->request->getGet('search');
        if (password_verify(session_id(), $sid)) {
            try {
                $data = $this->studentModel->getStudentList($searchTerm);
                if ($data === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // PUT
    public function addStudent()
    {
        $sid = $this->request->getPost('sid');
        $semesterId = $this->request->getPost('semester_id');
        $nim = $this->request->getPost('nim');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($semesterId) || empty($nim) || empty($name)) return $this->response->setStatusCode(403);
            try {
                $insertedRow = $this->studentModel->addStudent($semesterId, $nim, $name);
                if ($insertedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($insertedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function editStudent()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        $semesterId = $this->request->getPost('semester_id');
        $nim = $this->request->getPost('nim');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($id) || empty($nim) || empty($name)) return $this->response->setStatusCode(403);
            try {
                $updatedRow = $this->studentModel->editStudent($id, $semesterId, $nim, $name);
                if ($updatedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($updatedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // DELETE
    public function deleteStudent()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        if (password_verify(session_id(), $sid)) {
            if (empty($id)) return $this->response->setStatusCode(403);
            try {
                $deletedRow = $this->studentModel->deleteStudent($id);
                if ($deletedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($deletedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // PUT
    public function importStudent()
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
                $checkTemplate2 = strtolower(trim($sheet->getCell('B2')->getValue())) == 'nama mahasiswa';
                $checkTemplate3 = strtolower(trim($sheet->getCell('C2')->getValue())) == 'nim';
                $checkTemplate4 = strtolower(trim($sheet->getCell('D2')->getValue())) == 'semester';
                $checkTemplate5 = strtolower(trim($sheet->getCell('E2')->getValue())) == 'tanggal ditambahkan';
                if ($checkTemplate1 && $checkTemplate2 && $checkTemplate3 && $checkTemplate4 && $checkTemplate5)
                    $startRow = 3;
                else return $this->response->setStatusCode(403);
                $highestRow = $sheet->getHighestRow();
                $highestRow = count(array_filter(array_map('array_filter', $sheet->rangeToArray("B1:B$highestRow")))) + 1;
                $nims = array();
                $names = array();
                $semesterNames = array();
                $createdOns = array();
                for ($i = $startRow; $i <= $highestRow; $i++) {
                    $names[] = trim($sheet->getCell("B$i")->getValue());
                    $nims[] = trim($sheet->getCell("C$i")->getValue());
                    $semesterNames[] = trim($sheet->getCell("D$i")->getValue());
                    $createdOn = trim($sheet->getCell("E$i")->getValue());
                    if (!empty($createdOn)) $createdOn = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i', $createdOn)->getTimestamp());
                    else $createdOn = null;
                    $createdOns[] = $createdOn;
                }
                $result = $this->studentModel->importStudent($semesterNames, $nips, $names, $createdOns);
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // DELETE
    public function truncateStudent()
    {
        $sid = $this->request->getPost('sid');
        if (password_verify(session_id(), $sid)) {
            try {
                $result = $this->studentModel->truncateStudent();
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }
}