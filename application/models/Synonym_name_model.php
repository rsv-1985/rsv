<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Synonym_name_model extends Default_model
{
    public $table = 'synonym_name';

    public function update_name($name, $name2){
        $this->db->where('name',$name);
        $this->db->set('name',$name2);
        $this->db->update('product');
    }

    public function get_synonym_names(){
        $return = false;
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $return = [];
            $results = $query->result_array();
            foreach($results as $result){
                $return [$result['name']] = $result['name2'];
            }
        }
        return $return;
    }
}