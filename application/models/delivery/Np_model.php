<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Np_model extends Default_model
{
    public $table = 'np';
    public function save_form($data){
        $this->db->insert($this->table,$data);
    }

    public function get_form_data($order_id){
        $this->db->where('order_id',(int)$order_id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function delete_by_order($order_id){
        $this->db->where('order_id',(int)$order_id);
        $this->db->delete($this->table);
    }
}