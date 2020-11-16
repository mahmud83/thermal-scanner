<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\StudentModel;
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
        $this->studentModel = new StudentModel($this->authenticationModel);
    }

    // GET
    public function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/student', [
            'session' => $session,
            'title' => 'Manajemen Mahasiswa'
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

    // POST
    public function getFilteredStudentList()
    {
        $sid = $this->request->getPost('sid');
        $classIds = json_decode($this->request->getPost('class_ids'));
        $semesterIds = json_decode($this->request->getPost('semester_ids'));
        if (password_verify(session_id(), $sid)) {
            try {
                if (!empty($classIds) && !is_array($classIds)) $classIds = [$classIds];
                if (!empty($semesterIds) && !is_array($semesterIds)) $semesterIds = [$semesterIds];
                $data = $this->studentModel->getFilteredStudentList($classIds, $semesterIds);
                if ($data === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function addStudent()
    {
        $sid = $this->request->getPost('sid');
        $classId = $this->request->getPost('class_id');
        $semesterId = $this->request->getPost('semester_id');
        $nim = $this->request->getPost('nim');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($classId) || empty($semesterId) || empty($nim) || empty($name))
                return $this->response->setStatusCode(403);
            try {
                $insertedRow = $this->studentModel->addStudent($classId, $semesterId, $nim, $name);
                if ($insertedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($insertedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function addBatchStudent()
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
                $checkTemplate4 = strtolower(trim($sheet->getCell('D2')->getValue())) == 'kelas';
                $checkTemplate5 = strtolower(trim($sheet->getCell('E2')->getValue())) == 'prodi';
                $checkTemplate6 = strtolower(trim($sheet->getCell('F2')->getValue())) == 'semester';
                $checkTemplate7 = strtolower(trim($sheet->getCell('G2')->getValue())) == 'tanggal ditambahkan';
                if ($checkTemplate1 && $checkTemplate2 && $checkTemplate3 && $checkTemplate4 &&
                    $checkTemplate5 && $checkTemplate6 && $checkTemplate7) $startRow = 3;
                else return $this->response->setStatusCode(403);
                $highestRow = $sheet->getHighestRow();
                $highestRow = count(array_filter(array_map('array_filter', $sheet->rangeToArray("B1:B$highestRow")))) + 1;
                $nims = array();
                $names = array();
                $classNames = array();
                $studyProgramNames = array();
                $semesterNames = array();
                $createdOns = array();
                for ($i = $startRow; $i <= $highestRow; $i++) {
                    $names[] = trim($sheet->getCell("B$i")->getValue());
                    $nims[] = trim($sheet->getCell("C$i")->getValue());
                    $classNames[] = trim($sheet->getCell("D$i")->getValue());
                    $studyProgramNames[] = trim($sheet->getCell("E$i")->getValue());
                    $semesterNames[] = trim($sheet->getCell("F$i")->getValue());
                    $createdOn = trim($sheet->getCell("G$i")->getValue());
                    if (!empty($createdOn)) $createdOn = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i', $createdOn)->getTimestamp());
                    else $createdOn = null;
                    $createdOns[] = $createdOn;
                }
                $result = $this->studentModel->addBatchStudent($classNames, $studyProgramNames, $semesterNames, $nims, $names, $createdOns);
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
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
        $classId = $this->request->getPost('class_id');
        $semesterId = $this->request->getPost('semester_id');
        $nim = $this->request->getPost('nim');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($id) || empty($classId) || empty($semesterId) || empty($nim) || empty($name))
                return $this->response->setStatusCode(403);
            try {
                $updatedRow = $this->studentModel->editStudent($id, $classId, $semesterId, $nim, $name);
                if ($updatedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($updatedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
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

    // POST
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
                $checkTemplate4 = strtolower(trim($sheet->getCell('D2')->getValue())) == 'kelas';
                $checkTemplate5 = strtolower(trim($sheet->getCell('E2')->getValue())) == 'prodi';
                $checkTemplate6 = strtolower(trim($sheet->getCell('F2')->getValue())) == 'semester';
                $checkTemplate7 = strtolower(trim($sheet->getCell('G2')->getValue())) == 'tanggal ditambahkan';
                if ($checkTemplate1 && $checkTemplate2 && $checkTemplate3 && $checkTemplate4 &&
                    $checkTemplate5 && $checkTemplate6 && $checkTemplate7) $startRow = 3;
                else return $this->response->setStatusCode(403);
                $highestRow = $sheet->getHighestRow();
                $highestRow = count(array_filter(array_map('array_filter', $sheet->rangeToArray("B1:B$highestRow")))) + 1;
                $nims = array();
                $names = array();
                $classNames = array();
                $studyProgramNames = array();
                $semesterNames = array();
                $createdOns = array();
                for ($i = $startRow; $i <= $highestRow; $i++) {
                    $names[] = trim($sheet->getCell("B$i")->getValue());
                    $nims[] = trim($sheet->getCell("C$i")->getValue());
                    $classNames[] = trim($sheet->getCell("D$i")->getValue());
                    $studyProgramNames[] = trim($sheet->getCell("E$i")->getValue());
                    $semesterNames[] = trim($sheet->getCell("F$i")->getValue());
                    $createdOn = trim($sheet->getCell("G$i")->getValue());
                    if (!empty($createdOn)) $createdOn = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i', $createdOn)->getTimestamp());
                    else $createdOn = null;
                    $createdOns[] = $createdOn;
                }
                $result = $this->studentModel->importStudent($classNames, $studyProgramNames, $semesterNames, $nims, $names, $createdOns);
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
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
