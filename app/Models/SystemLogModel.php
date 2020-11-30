<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class SystemLogModel extends Model
{

    protected $db, $session;

    function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    public function getLogList()
    {
        $this->db->transBegin();
        $data = $this->db->table('system_log')
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

    public function getLatestLog()
    {
        $this->db->transBegin();
        $data = $this->db->table('system_log')
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

    public function saveLog(int $type, string $ip, string $event)
    {
        $this->db->transBegin();
        $logType = $this->db->table('system_log_type')->getWhere(['name' => $type])->getRow()->id;
        $this->db->table('system_ack_log')->insert([
            'type' => $logType,
            'event' => $event,
            'ip' => $ip
        ]);
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    public function resetLog()
    {
        $this->db->transBegin();
        $this->db->table('system_log')->truncate();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

}
