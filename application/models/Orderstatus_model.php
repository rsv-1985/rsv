<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Orderstatus_model extends Default_model{
    public $table = 'order_status';

    public function update($field, $value){
        $this->db->update($this->table,array($field => $value));
    }

    public function get_default(){
        $this->db->where('is_new', true);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }

    public function status_get_all(){
        $statuses = $this->db->get($this->table)->result_array();
        $return = [];
        foreach($statuses as $status){
            $return[$status['id']] = $status;
        }
        return $return;
    }
}