<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Synonym_model extends Default_model
{
    public $table = 'synonym';

    public function get_synonyms(){
        $return = false;
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $return = [];
            $results = $query->result_array();
            foreach($results as $result){
                $return [trim(mb_strtoupper($result['brand1'],'UTF-8'))] = trim(mb_strtoupper($result['brand2'],'UTF-8'));
            }
        }
        return $return;
    }
}