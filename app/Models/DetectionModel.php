<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class DetectionModel extends Model
{

    protected $db, $session;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    public function getDetectionList()
    {
        $this->db->transBegin();
        $data = $this->db->table('detection_history')
            ->orderBy('created_on', 'DESC')
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

    public function getLatestDetection()
    {
        $this->db->transBegin();
        $data = $this->db->table('detection_history')
            ->orderBy('created_on', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    public function saveDetection(string $ip, float $temperature)
    {
        $this->db->transBegin();
        $this->db->table('detection_history')->insert([
            'ip' => $ip,
            'temperature' => $temperature
        ]);
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    public function deleteDetection(int $id)
    {
        $this->db->transBegin();
        $this->db->table('detection_history')->delete(['id' => $id]);
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    public function resetDetection()
    {
        $this->db->transBegin();
        $this->db->table('detection_history')->truncate();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }
}
