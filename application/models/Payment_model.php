<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Payment_model extends Default_model{
    public $table = 'payment_method';

    public function payment_get_all(){
        $results = $this->db->get($this->table)->result_array();
        $return = [];
        foreach($results as $result){
            $return[$result['id']] = $result;
        }
        return $return;
    }
}