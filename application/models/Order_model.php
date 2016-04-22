<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Order_model extends Default_model{
    public $table = 'order';

    public function get_status_totals($statuses){
        if($statuses){
            foreach ($statuses as $status_id => $value){
                $sql = "(SELECT SUM(total) FROM ax_order LEFT JOIN ax_customer ON ax_customer.id = ax_order.customer_id WHERE ax_order.status ='".$status_id."'";
                if($this->input->get()) {
                    if ($this->input->get('login')) {
                        $sql .= " AND login = '".$this->input->get('login', true)."'";
                    }
                    if ($this->input->get('id')) {
                        $sql .= " AND ax_order.id = '".(int)$this->input->get('id', true)."'";
                    }
                    if ($this->input->get('first_name')) {
                        $sql .= " AND ax_order.first_name LIKE '%".$this->input->get('first_name', true)."%'";
                    }
                    if ($this->input->get('last_name')) {
                        $sql .= " AND ax_order.last_name LIKE '%".$this->input->get('last_name', true)."%'";
                    }
                    if ($this->input->get('email')) {
                        $sql .= " AND ax_order.email LIKE '%".$this->input->get('email', true)."%'";
                    }
                    if ($this->input->get('telephone')) {
                        $sql .= " AND ax_order.telephone LIKE '%".$this->input->get('telephone', true)."%'";
                    }
                    if ($this->input->get('delivery_method_id')) {
                        $sql .= " AND ax_order.delivery_method_id = '".(int)$this->input->get('delivery_method_id', true)."'";
                    }
                    if ($this->input->get('payment_method_id')) {
                        $sql .= " AND ax_order.payment_method_id = '".(int)$this->input->get('payment_method_id', true)."'";
                    }
                }
                $sql .= ") as sum_".$status_id;
                $this->db->select($sql);
            }
           
            $query = $this->db->get($this->table);

            return $query->row_array();
        }

        return false;
    }

    public function order_count_all(){
        $this->db->select('order.*, customer.login', false);
        $this->db->join('customer', 'customer.id = order.customer_id', 'left');
        if($this->input->get()){
            if($this->input->get('login')){
                $this->db->where('login', $this->input->get('login', true));
            }
            if($this->input->get('id')){
                $this->db->where('order.id', (int)$this->input->get('id', true));
            }
            if($this->input->get('first_name')){
                $this->db->like('order.first_name', $this->input->get('first_name', true));
            }
            if($this->input->get('last_name')){
                $this->db->like('order.last_name', $this->input->get('last_name', true));
            }
            if($this->input->get('email')){
                $this->db->like('order.email', $this->input->get('email', true));
            }
            if($this->input->get('telephone')){
                $this->db->like('order.telephone', $this->input->get('telephone', true));
            }
            if($this->input->get('delivery_method_id')){
                $this->db->where('order.delivery_method_id', (int)$this->input->get('delivery_method_id', true));
            }
            if($this->input->get('payment_method_id')){
                $this->db->where('order.payment_method_id', (int)$this->input->get('payment_method_id', true));
            }
            if($this->input->get('status')){
                $this->db->where('order.status', (int)$this->input->get('status', true));
            }
            return $this->db->count_all_results($this->table);
        }else{
            return $this->db->count_all($this->table);
        }
    }

    public function order_get_all($limit = false, $start = false){
        $this->db->select('order.*, customer.login', false);
        $this->db->join('customer', 'customer.id = order.customer_id', 'left');
        if($this->input->get()){
            if($this->input->get('login')){
                $this->db->where('login', $this->input->get('login', true));
            }
            if($this->input->get('id')){
                $this->db->where('order.id', (int)$this->input->get('id', true));
            }
            if($this->input->get('first_name')){
                $this->db->like('order.first_name', $this->input->get('first_name', true));
            }
            if($this->input->get('last_name')){
                $this->db->like('order.last_name', $this->input->get('last_name', true));
            }
            if($this->input->get('email')){
                $this->db->like('order.email', $this->input->get('email', true));
            }
            if($this->input->get('telephone')){
                $this->db->like('order.telephone', $this->input->get('telephone', true));
            }
            if($this->input->get('delivery_method_id')){
                $this->db->where('order.delivery_method_id', (int)$this->input->get('delivery_method_id', true));
            }
            if($this->input->get('payment_method_id')){
                $this->db->where('order.payment_method_id', (int)$this->input->get('payment_method_id', true));
            }
            if($this->input->get('status')){
                $this->db->where('order.status', (int)$this->input->get('status', true));
            }
        }
        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }
        $this->db->order_by('order.id', 'DESC');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}