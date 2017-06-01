<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Default_model extends CI_Model{
    public $table;

    public function get_table_name(){
        return $this->table;
    }

    public function count_all($where = false){
        if($where){
            foreach($where as $field => $value){
                $this->db->where($field, $value);
            }
            return $this->db->count_all_results($this->table);
        }else{
            return $this->db->count_all($this->table);
        }
    }

    public function insert($data, $id = false){
        if($id){
            $this->db->where('id',(int)$id);
            $this->db->update($this->table, $data);
            return $id;
        } else {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }

    public function insert_batch($data){
        $this->db->insert_batch($this->table, $data);
    }

    public function delete($id){
        $this->db->where('id',(int)$id);
        $this->db->delete($this->table);
    }

    public function get($id){
        $this->db->where('id', (int)$id);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }

    public function get_all($limit = false, $start = false, $where = false, $order = false){
        $this->db->select('*');
        $this->db->from($this->table);
        if($where){
            foreach($where as $field => $value){
                if($value != ''){
                    $this->db->where($field, $value);
                }
            }
        }
        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }

        if($order){
            foreach($order as $field => $value){
                $this->db->order_by($field, $value);
            }
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function truncate(){
        $this->db->truncate($this->table);
    }
}