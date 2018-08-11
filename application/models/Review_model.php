<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Review_model extends Default_model{
    public $table = 'review';

    public function getAvg($where){
        foreach ($where as $field => $value){
            $this->db->where((string)$field,(string)$value);
        }
        $this->db->select_avg('rating');
        return $this->db->get($this->table)->row_array();
    }
}