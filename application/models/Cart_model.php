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

    public function get_all($limit = false, $start = false, $where = false, $order = false){
        $this->db->select('*');
        $this->db->from($this->table);
        if($where){
            foreach($where as $field => $value){
                $this->db->where($field, $value);
            }
        }
        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }

        if($order){
            foreach($order as $field => $value){
                $this->db->order_by($field, $value);
            }
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function count_all($where = false){
        if($where){
            foreach($where as $field => $value){
                $this->db->where($field, $value);
            }
            return $this->db->count_all_results($this->table);
        }else{
            return $this->db->count_all($this->table);
        }
    }
}