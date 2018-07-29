<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Keys_model extends Default_model{

    public $table = 'keys';
    public $total_rows = 0;

    public function keys_get_all($limit, $start){
        $this->db->from($this->table);
        $this->db->join('customer','customer.id=keys.user_id');
        $this->db->select('SQL_CALC_FOUND_ROWS `ax_keys`.*, CONCAT_WS(" ", ax_customer.phone, ax_customer.first_name, ax_customer.second_name) as customer_name', false);
        $this->db->limit((int)$limit, (int)$start);
        $query = $this->db->get();
        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function keys_get($id){
        $this->db->from($this->table);
        $this->db->select('keys.*, ax_customer.id', false);
        $this->db->join('customer','customer.id=keys.user_id');
        $this->db->where('keys.id',(int)$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

}