<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\StudentClassModel;
use DateTime;

class StudentClass extends BaseController
{

    protected $authenticationModel, $classModel;

    function __construct()
    {
		helper('url');
		$this->authenticationModel = new AuthenticationModel();
		$this->classModel = new StudentClassModel();
    }

	public function index()
	{
		if($this->authenticationModel->getSession()) return view('pages/student_class', ['title' => 'Manajemen Kelas']);
		else redirect('signin');
	}

	public function getClassList()
	{
		$sid = $this->request->getGet('sid');
		$searchTerm = $this->request->getGet('search');
		if(password_verify(session_id(), $sid)) {
			try {
				$data = $this->classModel->getClassList($searchTerm);
				if($data === null) return $this->response->setStatusCode(500);
				else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function addClass()
	{
		$sid = $this->request->getPost('sid');
		$name = $this->request->getPost('name');
		if(password_verify(session_id(), $sid)) {
			if(empty($name)) return $this->response->setStatusCode(403);
			try {
				$insertedRow = $this->classModel->addClass($name);
				if($insertedRow === null) return $this->response->setStatusCode(500);
				else return $this->response->setStatusCode(200)->setJSON($insertedRow);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function editClass()
	{
		$sid = $this->request->getPost('sid');
		$id = $this->request->getPost('id');
		$name = $this->request->getPost('name');
		if(password_verify(session_id(), $sid)) {
			if(empty($id) || empty($name)) return $this->response->setStatusCode(403);
			try {
				$updatedRow = $this->classModel->editClass($id, $name);
				if($updatedRow === null) return $this->response->setStatusCode(500);
				else return $this->response->setStatusCode(200)->setJSON($updatedRow);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function deleteClass()
	{
		$sid = $this->request->getPost('sid');
		$id = $this->request->getPost('id');
		if(password_verify(session_id(), $sid)) {
			if(empty($id)) return $this->response->setStatusCode(403);
			try {
				$deletedRow = $this->classModel->deleteClass($id);
				if($deletedRow === null) return $this->response->setStatusCode(500);
				else return $this->response->setStatusCode(200)->setJSON($deletedRow);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function importClass()
	{
		$sid = $this->request->getPost('sid');
		$file = $this->request->getFile('file');
		if(password_verify(session_id(), $sid)) {
			if(empty($file)) return $this->response->setStatusCode(403);
			try {
				$fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
				$sheet = $reader->load($file)->getActiveSheet();
				$checkTemplate1 = strtolower(trim($sheet->getCell('A2')->getValue())) == 'no.';
				$checkTemplate2 = strtolower(trim($sheet->getCell('B2')->getValue())) == 'nama kelas';
				$checkTemplate3 = strtolower(trim($sheet->getCell('C2')->getValue())) == 'tanggal ditambahkan';
				if($checkTemplate1 && $checkTemplate2 && $checkTemplate3) $startRow = 3;
				else return $this->response->setStatusCode(403);
				$highestRow = $sheet->getHighestRow();
				$highestRow = count(array_filter(array_map('array_filter', $sheet->rangeToArray("A1:A$highestRow"))));
				$names = array();
				$createdOns = array();
				for ($i = $startRow; $i <= $highestRow; $i++) { 
					$names[] = trim($sheet->getCell("B$i")->getValue());
					$createdOn = trim($sheet->getCell("C$i")->getValue());
					if(!empty($createdOn)) $createdOn = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $createdOn)->getTimestamp());
					else $createdOn = null;
					$createdOns[] = $createdOn;
				}
				$result = $this->classModel->importClass($names, $createdOns);
				if($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
				else return $this->response->setStatusCode(500);
			} catch(\Exception $e) {
				print_r($e);
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function truncateClass()
	{
		$sid = $this->request->getPost('sid');
		if(password_verify(session_id(), $sid)) {
			try {
				$result = $this->classModel->truncateClass();
				if($result) return $this->response->setStatusCode(200)->setJSON(['success' => true]);
				else return $this->response->setStatusCode(500);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}
}