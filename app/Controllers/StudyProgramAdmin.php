<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\StudyProgramAdminModel;
use Exception;

class StudyProgramAdmin extends BaseController
{

    protected $authenticationModel, $studyProgramAdminModel;

    function __construct()
    {
        helper('url');
        $this->authenticationModel = new AuthenticationModel();
        $this->studyProgramAdminModel = new StudyProgramAdminModel();
    }

    // GET
    public function index()
    {
        $session = $this->authenticationModel->getSession();
        if (empty($session->user_name)) return redirect('signout');
        return view('pages/study_program_admin', [
            'session' => $session,
            'title' => 'Manajemen Administrator Prodi'
        ]);
    }

    // GET
    public function getAdminList()
    {
        $sid = $this->request->getGet('sid');
        $searchTerm = $this->request->getGet('search');
        if (password_verify(session_id(), $sid)) {
            try {
                $data = $this->studyProgramAdminModel->getAdminList($searchTerm);
                if ($data === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function addAdmin()
    {
        $sid = $this->request->getPost('sid');
        $studyProgramId = $this->request->getPost('study_program');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($studyProgramId) || empty($email) || empty($password) || empty($confirmPassword) || empty($name) ||
                $password != $confirmPassword || strlen($password) < 4 || !filter_var($email, FILTER_VALIDATE_EMAIL))
                return $this->response->setStatusCode(403);
            try {
                $insertedRow = $this->studyProgramAdminModel->addAdmin($studyProgramId, $email, $password, $name);
                if ($insertedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($insertedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function editAdmin()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        $newStudyProgramId = $this->request->getPost('study_program');
        $oldStudyProgramId = $this->request->getPost('old_study_program');
        $email = $this->request->getPost('email');
        $name = $this->request->getPost('name');
        if (password_verify(session_id(), $sid)) {
            if (empty($id) || empty($oldStudyProgramId) || empty($newStudyProgramId) || empty($email) ||
                empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                return $this->response->setStatusCode(403);
            try {
                $updatedRow = $this->studyProgramAdminModel->editAdmin($id, $oldStudyProgramId, $newStudyProgramId, $email, $name);
                if ($updatedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($updatedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function resetAdminPassword()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');
        if (password_verify(session_id(), $sid)) {
            if (empty($id) || empty($password) || empty($confirmPassword) || $password != $confirmPassword)
                return $this->response->setStatusCode(403);
            try {
                $updatedRow = $this->studyProgramAdminModel->resetAdminPassword($id, $password);
                if ($updatedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($updatedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function deleteAdmin()
    {
        $sid = $this->request->getPost('sid');
        $id = $this->request->getPost('id');
        if (password_verify(session_id(), $sid)) {
            if (empty($id)) return $this->response->setStatusCode(403);
            try {
                $deletedRow = $this->studyProgramAdminModel->deleteAdmin($id);
                if ($deletedRow === null) return $this->response->setStatusCode(500);
                else return $this->response->setStatusCode(200)->setJSON($deletedRow);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }

    // POST
    public function truncateAdmin()
    {
        $sid = $this->request->getPost('sid');
        if (password_verify(session_id(), $sid)) {
            try {
                $result = $this->studyProgramAdminModel->truncateAdmin();
                if ($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
                else return $this->response->setStatusCode(500);
            } catch (Exception $e) {
                return $this->response->setStatusCode(500)->setJSON($e->getMessage());
            }
        } else return $this->response->setStatusCode(401);
    }
}