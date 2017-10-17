<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Payment_model extends Default_model{
    public $table = 'payment_method';

    public function payment_get($id){
        $this->db->where('id',(int)$id);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $result = $query->row_array();
            $result['delivery_methods'] = unserialize($result['delivery_methods']);
            return $result;
        }
        return false;
    }

    public function payment_get_all(){
        $results = $this->db->get($this->table)->result_array();
        $return = [];
        foreach($results as $result){
            $return[$result['id']] = $result;
        }
        $return[0] = [
            'id' => 0,
            'name' => 'С баланса'
        ];
        return $return;
    }
}