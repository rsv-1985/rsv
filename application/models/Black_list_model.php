<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Black_list_model extends Default_model{
    public $table = 'black_list';

    public function get($customer_id){
        $this->db->where('customer_id', (int)$customer_id);
        return $this->db->get($this->table)->row_array();
    }

    public function delete($customer_id){
        $this->db->where('customer_id',(int)$customer_id)->delete($this->table);
    }
}