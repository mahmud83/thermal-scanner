<?php namespace App\Controllers;

use App\Models\AuthenticationModel;
use App\Models\ScheduleModel;
use Endroid\QrCode\QrCode;
use DateTime;

class Schedule extends BaseController
{
    protected $authenticationModel, $scheduleModel;

    function __construct()
    {
		helper('url');
		$this->authenticationModel = new AuthenticationModel();
		$this->scheduleModel = new ScheduleModel();
    }

	public function index()
	{
		if($this->authenticationModel->getSession()) return view('pages/schedule', ['title' => 'Manajemen Jadwal']);
		else redirect('signin');
	}

	public function getScheduleList()
	{
		$sid = $this->request->getGet('sid');
		if(password_verify(session_id(), $sid)) {
			try {
				$data = $this->scheduleModel->getScheduleList();
				if($data === null) return $this->response->setStatusCode(500);
				else return $this->response->setStatusCode(200)->setJSON(['data' => $data]);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function addSchedule()
	{
		$sid = $this->request->getPost('sid');
		$classID = $this->request->getPost('class_id');
		$lecturerID = $this->request->getPost('lecturer_id');
		$name = $this->request->getPost('name');
		$dateStart = $this->request->getPost('date_start');
		$dateEnd = $this->request->getPost('date_end');
		if(password_verify(session_id(), $sid)) {
            if(empty($classID) || empty($lecturerID) || empty($name) || empty($dateStart) || empty($dateEnd)) return $this->response->setStatusCode(403);
			try {
				$dateStart = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateStart)->getTimestamp());
				$dateEnd = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateEnd)->getTimestamp());
                $insertedRow = $this->scheduleModel->addSchedule($classID, $lecturerID, $name, $dateStart, $dateEnd);
                if($insertedRow === null) return $this->response->setStatusCode(500);
                $qrCodeID = time();
                $qrCodeFileName = "sch_qrcode-{$insertedRow->id}.png";
                $qrCode = new QrCode("{$qrCodeID}sch{$insertedRow->id}");
				$qrCode->setSize(300);
				$qrCode->setMargin(10);
				$qrCode->writeFile("./assets/generated/qr_code/{$qrCodeFileName}");
                $insertedRow = $this->scheduleModel->addScheduleQrCode($insertedRow->id, $qrCodeFileName);
				if($insertedRow === null) return $this->response->setStatusCode(500);
				else return $this->response->setStatusCode(200)->setJSON($insertedRow);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setJSON($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function editSchedule()
	{
		$sid = $this->request->getPost('sid');
		$id = $this->request->getPost('id');
		$classID = $this->request->getPost('class_id');
		$lecturerID = $this->request->getPost('lecturer_id');
		$name = $this->request->getPost('name');
		$dateStart = $this->request->getPost('date_start');
		$dateEnd = $this->request->getPost('date_end');
		if(password_verify(session_id(), $sid)) {
            if(empty($id) || empty($classID) || empty($lecturerID) || empty($name) || empty($dateStart) || empty($dateEnd)) return $this->response->setStatusCode(403);
			try {
				$dateStart = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateStart)->getTimestamp());
				$dateEnd = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateEnd)->getTimestamp());
				$updatedRow = $this->scheduleModel->editSchedule($id, $classID, $lecturerID, $name, $dateStart, $dateEnd);
				if($updatedRow === null) return $this->response->setStatusCode(500);
				else return $this->response->setStatusCode(200)->setJSON($updatedRow);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function deleteSchedule()
	{
		$sid = $this->request->getPost('sid');
		$id = $this->request->getPost('id');
		if(password_verify(session_id(), $sid)) {
			if(empty($id)) return $this->response->setStatusCode(403);
			try {
				$deletedRow = $this->scheduleModel->deleteSchedule($id);
				if($deletedRow === null) return $this->response->setStatusCode(500);
				else {
					$qrCode = "./assets/generated/qr_code/{$deletedRow->qr_code}";
					if(!empty($qrCode) && is_file($qrCode)) unlink($qrCode);
					return $this->response->setStatusCode(200)->setJSON($deletedRow);
				}
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function importSchedule()
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
				$checkTemplate2 = strtolower(trim($sheet->getCell('B2')->getValue())) == 'nama jadwal';
				$checkTemplate3 = strtolower(trim($sheet->getCell('C2')->getValue())) == 'kelas';
				$checkTemplate4 = strtolower(trim($sheet->getCell('D2')->getValue())) == 'dosen';
				$checkTemplate5 = strtolower(trim($sheet->getCell('E2')->getValue())) == 'waktu mulai';
				$checkTemplate6 = strtolower(trim($sheet->getCell('F2')->getValue())) == 'waktu selesai';
				$checkTemplate7 = strtolower(trim($sheet->getCell('G2')->getValue())) == 'tanggal ditambahkan';
                if( $checkTemplate1 && $checkTemplate2 && $checkTemplate3 && $checkTemplate4 &&
                    $checkTemplate5 && $checkTemplate6 && $checkTemplate7) $startRow = 3;
				else return $this->response->setStatusCode(403);
				$highestRow = $sheet->getHighestRow();
				$highestRow = count(array_filter(array_map('array_filter', $sheet->rangeToArray("A1:A$highestRow"))));
                $names = array();
                $classNames = array();
                $lecturerNames = array();
                $dateStarts = array();
                $dateEnds = array();
                $createdOns = array();
				for ($i = $startRow; $i <= $highestRow; $i++) { 
					$names[] = trim($sheet->getCell("B$i")->getValue());
					$classNames[] = trim($sheet->getCell("C$i")->getValue());
					$lecturerNames[] = trim($sheet->getCell("D$i")->getValue());
					$dateStart = trim($sheet->getCell("E$i")->getValue());
					if(!empty($dateStart)) $dateStart = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateStart)->getTimestamp());
					else $dateStart = null;
					$dateStarts[] = $dateStart;
					$dateEnd = trim($sheet->getCell("F$i")->getValue());
					if(!empty($dateEnd)) $dateEnd = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $dateEnd)->getTimestamp());
					else $dateEnd = null;
					$dateEnds[] = $dateStart;
					$createdOn = trim($sheet->getCell("G$i")->getValue());
					if(!empty($createdOn)) $createdOn = date('Y-m-d H:i:s', DateTime::createFromFormat('d/m/Y H:i A', $createdOn)->getTimestamp());
					else $createdOn = null;
					$createdOns[] = $createdOn;
				}
				$insertedRows = $this->scheduleModel->importSchedule($classNames, $lecturerNames, $names, $dateStarts, $dateEnds, $createdOns);
				if($insertedRows === null) return $this->response->setStatusCode(500);
				else {
					array_map('unlink', array_filter((array) glob('./assets/generated/qr_code/*')));
					foreach($insertedRows as $insertedRow) {
						$qrCodeID = time();
						$qrCodeFileName = "sch_qrcode-{$insertedRow->id}.png";
						$qrCode = new QrCode("{$qrCodeID}sch{$insertedRow->id}");
						$qrCode->setSize(300);
						$qrCode->setMargin(10);
						$qrCode->writeFile("./assets/generated/qr_code/{$qrCodeFileName}");
						if($this->scheduleModel->addScheduleQrCode($insertedRow->id, $qrCodeFileName) === null) return $this->response->setStatusCode(500);
					}
					return $this->response->setStatusCode(200)->setJSON(['success' => true]);
				}
			} catch(\Exception $e) {
				print_r($e);
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function truncateSchedule()
	{
		$sid = $this->request->getPost('sid');
		if(password_verify(session_id(), $sid)) {
			try {
				$result = $this->scheduleModel->truncateSchedule();
				if($result) {
					array_map('unlink', array_filter((array) glob('./assets/generated/qr_code/*')));
					return $this->response->setStatusCode(200)->setJSON(['success' => true]);
				} else return $this->response->setStatusCode(500);
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setJSON($e);
			}
		} else return $this->response->setStatusCode(401);
	}

	public function regenerateScheduleQRCode()
	{
		$sid = $this->request->getPost('sid');
		$id = $this->request->getPost('id');
		if(password_verify(session_id(), $sid)) {
			if(empty($id)) return $this->response->setStatusCode(403);
			try {
				$schedule = $this->scheduleModel->getSchedule($id);
				if($schedule === null) return $this->response->setStatusCode(500);
				else {
					$qrCode = "./assets/generated/qr_code/{$schedule->qr_code}";
					if(!empty($qrCode) && is_file($qrCode)) unlink($qrCode);
					$newQRCodeID = time();
					$newQrCodeFileName = "sch_qrcode-{$schedule->id}.png";
					$newQrCode = new QrCode("{$newQRCodeID}sch{$schedule->id}");
					$newQrCode->setSize(300);
					$newQrCode->setMargin(10);
					$newQrCode->writeFile($qrCode);
					$insertedRow = $this->scheduleModel->addScheduleQrCode($schedule->id, $newQrCodeFileName);
					if($insertedRow === null) return $this->response->setStatusCode(500);
					else return $this->response->setStatusCode(200)->setJSON($insertedRow);
				}
			} catch(\Exception $e) {
				return $this->response->setStatusCode(500)->setBody($e);
			}
		} else return $this->response->setStatusCode(401);
	}
}