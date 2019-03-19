<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mattribute extends Default_model{

    public function getAttributes(){
        $this->db->order_by('name', 'ASC');
        return $this->db->get('attribute')->result_array();
    }

    public function getValues($attribute_id){
        $this->db->where('attribute_id',(int)$attribute_id);
        $this->db->order_by('value', 'ASC');
        return $this->db->get('attribute_value')->result_array();
    }
}