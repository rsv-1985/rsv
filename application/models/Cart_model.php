<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Cart_model extends CI_Model{
    public $table = 'cart';

    public function cart_insert($cart_data, $customer_id){
        $this->db->where('customer_id',(int)$customer_id);
        $this->db->delete($this->table);

        $this->db->set('customer_id',(int)$customer_id);
        $this->db->set('cart_data',$cart_data);
        $this->db->insert($this->table);
    }

    public function cart_clear($customer_id){
        $this->db->where('customer_id',(int)$customer_id)->delete($this->table);
    }

    public function cart_get($customer_id){
        $this->db->where('customer_id',(int)$customer_id);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }

        return false;
    }
}