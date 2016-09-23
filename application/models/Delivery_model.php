<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Delivery_model extends Default_model{
    public $table = 'delivery_method';

    public function delivery_get($id){
        $this->db->where('id',(int)$id);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $result = $query->row_array();
            $result['payment_methods'] = unserialize($result['payment_methods']);
            return $result;
        }
        return false;
    }

    public function delivery_get_all(){
        $results = $this->db->get($this->table)->result_array();
        $return = [];
        foreach($results as $result){
            $return[$result['id']] = $result;
        }
        return $return;
    }
}