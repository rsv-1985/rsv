<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Order_model extends Default_model{
    public $table = 'order';

    public function order_count_all(){
        if($this->input->get()){
            if($this->input->get('id')){
                $this->db->where('id', (int)$this->input->get('id', true));
            }
            if($this->input->get('first_name')){
                $this->db->like('first_name', $this->input->get('first_name', true));
            }
            if($this->input->get('last_name')){
                $this->db->like('last_name', $this->input->get('last_name', true));
            }
            if($this->input->get('email')){
                $this->db->like('email', $this->input->get('email', true));
            }
            if($this->input->get('telephone')){
                $this->db->like('telephone', $this->input->get('telephone', true));
            }
            if($this->input->get('delivery_method_id')){
                $this->db->where('delivery_method_id', (int)$this->input->get('delivery_method_id', true));
            }
            if($this->input->get('payment_method_id')){
                $this->db->where('payment_method_id', (int)$this->input->get('payment_method_id', true));
            }
            if($this->input->get('status')){
                $this->db->where('status', (int)$this->input->get('status', true));
            }
            return $this->db->count_all_results($this->table);
        }else{
            return $this->db->count_all($this->table);
        }
    }

    public function order_get_all($limit = false, $start = false){
        if($this->input->get()){
            if($this->input->get('id')){
                $this->db->where('id', (int)$this->input->get('id', true));
            }
            if($this->input->get('first_name')){
                $this->db->like('first_name', $this->input->get('first_name', true));
            }
            if($this->input->get('last_name')){
                $this->db->like('last_name', $this->input->get('last_name', true));
            }
            if($this->input->get('email')){
                $this->db->like('email', $this->input->get('email', true));
            }
            if($this->input->get('telephone')){
                $this->db->like('telephone', $this->input->get('telephone', true));
            }
            if($this->input->get('delivery_method_id')){
                $this->db->where('delivery_method_id', (int)$this->input->get('delivery_method_id', true));
            }
            if($this->input->get('payment_method_id')){
                $this->db->where('payment_method_id', (int)$this->input->get('payment_method_id', true));
            }
            if($this->input->get('status')){
                $this->db->where('status', (int)$this->input->get('status', true));
            }
        }
        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}