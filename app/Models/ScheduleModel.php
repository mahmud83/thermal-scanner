<?php namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $db;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    function getSchedule($id)
    {
        $this->db->transBegin();
        $data = $this->db
                ->table('schedule_history')
                ->select('
                    schedule_history.id as id, schedule_history.name as name, schedule_history.date_start as date_start,
                    schedule_history.date_end as date_end, schedule_history.qr_code as qr_code,
                    schedule_history.created_on as created_on, class.id as class_id, class.name as class_name,
                    lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('class', 'class.id = schedule_history.class_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule_history.lecturer_id', 'left')
                ->getWhere(['schedule_history.id' => $id])
                ->getRow();
        if($this->db->transStatus() && !empty($data)) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getScheduleList()
    {
        $this->db->transBegin();
        $data = $this->db
                ->table('schedule_history')
                ->select('
                    schedule_history.id as id, schedule_history.name as name, schedule_history.date_start as date_start,
                    schedule_history.date_end as date_end, schedule_history.qr_code as qr_code,
                    schedule_history.created_on as created_on, class.id as class_id, class.name as class_name,
                    lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('class', 'class.id = schedule_history.class_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule_history.lecturer_id', 'left')
                ->orderBy('schedule_history.created_on', 'ASC')
                ->get()
                ->getResultArray();
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addSchedule($classID, $lecturerID, $name, $dateStart, $dateEnd)
    {
        $this->db->transBegin();
        $this->db->table('schedule_history')->insert([
            'schedule_history.class_id' => $classID,
            'schedule_history.lecturer_id' => $lecturerID,
            'schedule_history.name' => trim($name),
            'schedule_history.date_start' => trim($dateStart),
            'schedule_history.date_end' => trim($dateEnd)
        ]);
        $insertedRow = $this->db
                ->table('schedule_history')
                ->select('
                    schedule_history.id as id, schedule_history.name as name, schedule_history.date_start as date_start,
                    schedule_history.date_end as date_end, schedule_history.qr_code as qr_code,
                    schedule_history.created_on as created_on, class.id as class_id, class.name as class_name,
                    lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('class', 'class.id = schedule_history.class_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule_history.lecturer_id', 'left')
                ->getWhere(['schedule_history.id' => $this->db->insertID()])
                ->getRow();
        if($this->db->transStatus() && !empty($insertedRow)) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addScheduleQrCode($id, $qrCode)
    {
        $this->db->transBegin();
        $this->db->table('schedule_history')->where(['schedule_history.id' => $id])->update([
            'schedule_history.qr_code' => trim($qrCode)
        ]);
        $updatedRow = $this->db
                ->table('schedule_history')
                ->select('
                    schedule_history.id as id, schedule_history.name as name, schedule_history.date_start as date_start,
                    schedule_history.date_end as date_end, schedule_history.qr_code as qr_code,
                    schedule_history.created_on as created_on, class.id as class_id, class.name as class_name,
                    lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('class', 'class.id = schedule_history.class_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule_history.lecturer_id', 'left')
                ->getWhere(['schedule_history.id' => $id])
                ->getRow();
        if($this->db->transStatus() && !empty($updatedRow)) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editSchedule($id, $classID, $lecturerID, $name, $dateStart, $dateEnd)
    {
        $this->db->transBegin();
        $this->db->table('schedule_history')->where(['schedule_history.id' => $id])->update([
            'schedule_history.class_id' => $classID,
            'schedule_history.lecturer_id' => $lecturerID,
            'schedule_history.name' => trim($name),
            'schedule_history.date_start' => trim($dateStart),
            'schedule_history.date_end' => trim($dateEnd)
        ]);
        $updatedRow = $this->db
                ->table('schedule_history')
                ->select('
                    schedule_history.id as id, schedule_history.name as name, schedule_history.date_start as date_start,
                    schedule_history.date_end as date_end, schedule_history.qr_code as qr_code,
                    schedule_history.created_on as created_on, class.id as class_id, class.name as class_name,
                    lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('class', 'class.id = schedule_history.class_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule_history.lecturer_id', 'left')
                ->getWhere(['schedule_history.id' => $id])
                ->getRow();
        if($this->db->transStatus() && !empty($updatedRow)) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteSchedule($id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db
                ->table('schedule_history')
                ->select('
                    schedule_history.id as id, schedule_history.name as name, schedule_history.date_start as date_start,
                    schedule_history.date_end as date_end, schedule_history.qr_code as qr_code,
                    schedule_history.created_on as created_on, class.id as class_id, class.name as class_name,
                    lecturer.id as lecturer_id, lecturer.name as lecturer_name
                ')
                ->join('class', 'class.id = schedule_history.class_id', 'left')
                ->join('lecturer', 'lecturer.id = schedule_history.lecturer_id', 'left')
                ->getWhere(['schedule_history.id' => $id])
                ->getRow();
        $this->db->table('schedule_history')->where(['schedule_history.id' => $id])->delete();
        if($this->db->transStatus() && !empty($deletedRow)) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importSchedule($classNames, $lecturerNames, $names, $dateStarts, $dateEnds, $createdOns)
    {
        $this->db->transBegin();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('schedule_history')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $data = [
                'schedule_history.class_id' => $this->db->table('class')->getWhere(['lower(trim(class.name))' => strtolower(trim($classNames[$i]))])->getRow()->id,
                'schedule_history.lecturer_id' => $this->db->table('lecturer')->getWhere(['lower(trim(lecturer.name))' => strtolower(trim($lecturerNames[$i]))])->getRow()->id,
                'schedule_history.name' => trim($names[$i]),
                'schedule_history.date_start' => trim($dateStarts[$i]),
                'schedule_history.date_end' => trim($dateEnds[$i]),
            ];
            if(empty('schedule_history.class_id') || empty('schedule_history.lecturer_id')) {
                $this->db->transRollback();
                return null;
            }
            if(!empty($createdOns[$i])) $data['schedule_history.created_on'] = trim($createdOns[$i]);
            if( !empty($data['schedule_history.class_id']) && !empty($data['schedule_history.lecturer_id']) &&
                !empty($data['schedule_history.name']) && !empty($data['schedule_history.date_start']) &&
                !empty($data['schedule_history.date_end'])) {
                $this->db->table('schedule_history')->insert($data);
                $insertedRow[] = $this->db
                        ->table('schedule_history')
                        ->select('
                            schedule_history.id as id, schedule_history.name as name, schedule_history.date_start as date_start,
                            schedule_history.date_end as date_end, schedule_history.qr_code as qr_code,
                            schedule_history.created_on as created_on, class.id as class_id, class.name as class_name,
                            lecturer.id as lecturer_id, lecturer.name as lecturer_name
                        ')
                        ->join('class', 'class.id = schedule_history.class_id', 'left')
                        ->join('lecturer', 'lecturer.id = schedule_history.lecturer_id', 'left')
                        ->getWhere(['schedule_history.id' => $this->db->insertID()])
                        ->getRow();
            }
        }
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function truncateSchedule()
    {
        $this->db->transBegin();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('schedule_history')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }
}
