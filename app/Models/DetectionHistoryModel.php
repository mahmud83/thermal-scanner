<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class DetectionHistoryModel extends Model
{

    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    function getDetectionList()
    {
        $this->db->transBegin();
        $data = $this->db
            ->table('detection_history')
            ->orderBy('detection_history.created_on', 'DESC')
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
}