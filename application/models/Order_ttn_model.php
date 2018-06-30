<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Order_ttn_model extends Default_model{
    public $table = 'order_ttn';

    public function getByOrder($order_id){
        $this->db->where('order_id',(int)$order_id);
        return $this->db->get($this->table)->result_array();
    }
}