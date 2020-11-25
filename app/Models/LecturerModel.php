<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class LecturerModel extends Model
{

    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    function getLecturerList($searchTerm)
    {
        $this->db->transBegin();
        if (empty($searchTerm))
            $data = $this->db->table('lecturer')
                ->orderBy('lecturer.created_on', 'ASC')
                ->get()
                ->getResultArray();
        else
            $data = $this->db->table('lecturer')
                ->like('lower(trim(lecturer.name))', strtolower(trim($searchTerm)))
                ->like('lower(trim(lecturer.nip))', strtolower(trim($searchTerm)))
                ->like('date_format(lecturer.created_on, "%d/%m/%Y %h/%i %p")', strtolower(trim($searchTerm)))
                ->orderBy('lecturer.created_on', 'ASC')
                ->get()
                ->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addLecturer(string $nip, string $name)
    {
        $this->db->transBegin();
        $this->db->table('lecturer')->insert(['lecturer.nip' => (string)$nip, 'lecturer.name' => trim($name)]);
        $insertedRow = $this->db->table('lecturer')->getWhere(['lecturer.id' => $this->db->insertID()])->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editLecturer(int $id, string $nip, string $name)
    {
        $this->db->transBegin();
        $this->db->table('lecturer')->where(['lecturer.id' => $id])->update([
            'lecturer.nip' => (string)$nip,
            'lecturer.name' => trim($name)
        ]);
        $updatedRow = $this->db->table('lecturer')->getWhere(['lecturer.id' => $id])->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteLecturer(int $id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db->table('lecturer')->getWhere(['lecturer.id' => $id])->getRow();
        $this->db->table('lecturer')->where(['lecturer.id' => $id])->delete();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importLecturer(array $nips, array $names, array $createdOns)
    {
        $this->db->transBegin();
        $total = count($this->db->query('select * from lecturer')->getResultArray());
        $truncable = count($this->db->query('select * from lecturer left join schedule on schedule.lecturer_id = lecturer.id where schedule.lecturer_id is null')->getResultArray());
        if ($total != $truncable) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('lecturer')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $data = [
                'lecturer.nip' => trim($nips[$i]),
                'lecturer.name' => trim($names[$i]),
            ];
            if (!empty($createdOns[$i])) $data['lecturer.created_on'] = trim($createdOns[$i]);
            if (!empty($data['lecturer.nip']) && !empty($data['lecturer.name'])) $this->db->table('lecturer')->insert($data);
        }
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function truncateLecturer()
    {
        $this->db->transBegin();
        $total = count($this->db->query('select * from lecturer')->getResultArray());
        $truncable = count($this->db->query('select * from lecturer left join schedule on schedule.lecturer_id = lecturer.id where schedule.lecturer_id is null')->getResultArray());
        if ($total != $truncable) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('lecturer')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }
}
