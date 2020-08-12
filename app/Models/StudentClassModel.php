<?php namespace App\Models;

use CodeIgniter\Model;

class StudentClassModel extends Model
{
    protected $db;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    function getClassList($searchTerm)
    {
        $this->db->transBegin();
        if(empty($searchTerm))
            $data = $this->db->table('class')->orderBy('class.created_on', 'ASC')->get()->getResultArray();
        else
            $data = $this->db->table('class')->orderBy('class.created_on', 'ASC')
                    ->like(['lower(trim(class.name))' => strtolower(trim($searchTerm))])->get()->getResultArray();
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addClass($name)
    {
        $this->db->transBegin();
        $this->db->table('class')->insert(['class.name' => trim($name)]);
        $insertedRow = $this->db->table('class')->getWhere(['class.id' => $this->db->insertID()])->getRow();
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function editClass($id, $name)
    {
        $this->db->transBegin();
        $this->db->table('class')->where(['class.id' => $id])->update(['class.name' => trim($name)]);
        $updatedRow = $this->db->table('class')->getWhere(['class.id' => $id])->getRow();
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteClass($id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db->table('class')->getWhere(['class.id' => $id])->getRow();
        $this->db->table('class')->where(['class.id' => $id])->delete();
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importClass($names, $createdOns)
    {
        $this->db->transBegin();
        $total = count($this->db->query('select * from class')->getResultArray());
        $truncable = count($this->db->query('select * from class left join schedule_history on schedule_history.class_id = class.id where schedule_history.class_id is null')->getResultArray());
        if($total != $truncable) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('class')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $data['class.name'] = trim($names[$i]);
            if(!empty($createdOns[$i])) $data['class.created_on'] = trim($createdOns[$i]);
            if(!empty($data['class.name'])) $this->db->table('class')->insert($data);
        }
        if($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function truncateClass()
    {
        $this->db->transBegin();
        $total = count($this->db->query('select * from class')->getResultArray());
        $truncable = count($this->db->query('select * from class left join schedule_history on schedule_history.class_id = class.id where schedule_history.class_id is null')->getResultArray());
        if($total != $truncable) return false;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('class')->truncate();
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