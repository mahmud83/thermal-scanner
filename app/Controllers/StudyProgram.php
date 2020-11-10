<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\StudyProgramModel;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudyProgram extends BaseController
{

    protected $authenticationModel, $studyProgramModel;

    function __construct()
    {
        helper('url');
        $this->authenticationModel = new AuthenticationModel();
        $this->studyProgramModel = new StudyProgramModel();
    }

    // GET
    public function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/study_program', [
            'session' => $session,
            'title' => 'Manajemen Prodi'
        ]);
    }

    // GET
    public function getStudyProgramList()
    {
        $sid = $this->request->getGet('sid');
        $searchTerm = $this->request->getGet('search');
        if (password_verify(session_id(), $sid)) {
            try {
                $data = $this->studyProgramModel->getStudyProgramList($searchTerm);
                if ($data === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function addStudyProgram()
    {
        $sid = $this->request->getPost('sid');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($name)) return $this->response->setStatusCode(403);
            try {
                $insertedRow = $this->studyProgramModel->addStudyProgram($name);
                if ($insertedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($insertedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function editStudyProgram()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($id) || empty($name)) return $this->response->setStatusCode(403);
            try {
                $updatedRow = $this->studyProgramModel->editStudyProgram($id, $name);
                if ($updatedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($updatedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function deleteStudyProgram()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        if (password_verify(session_id(), $sid)) {
            if (empty($id)) return $this->response->setStatusCode(403);
            try {
                $deletedRow = $this->studyProgramModel->deleteStudyProgram($id);
                if ($deletedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($deletedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function importStudyProgram()
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
                $checkTemplate2 = strtolower(trim($sheet->getCell('B2')->getValue())) == 'nama prodi';
                $checkTemplate3 = strtolower(trim($sheet->getCell('C2')->getValue())) == 'tanggal ditambahkan';
                if ($checkTemplate1 && $checkTemplate2 && $checkTemplate3) $startRow = 3;
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
                $result = $this->studyProgramModel->importStudyProgram($names, $createdOns);
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function truncateStudyProgram()
    {
        $sid = $this->request->getPost('sid');
        if (password_verify(session_id(), $sid)) {
            try {
                $result = $this->studyProgramModel->truncateStudyProgram();
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }
}