<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Order_product_model extends Default_model{
    public $table = 'order_product';

    public function delete_by_order($id){
        $this->db->where('order_id', (int)$id);
        $this->db->delete($this->table);
    }
    //customer order product
    public function product_get($id){
        $this->db->select('p.*, s.name as status_name');
        $this->db->from('order_product p');
        $this->db->join('order_status s', 's.id = p.status_id');
        $this->db->where('order_id',(int)$id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }
}