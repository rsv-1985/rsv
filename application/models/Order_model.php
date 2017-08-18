<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Order_model extends Default_model{
    public $table = 'order';

    public $total_rows = 0;

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

    public function order_get_all_products($limit, $start){
        $this->db->select('SQL_CALC_FOUND_ROWS *', false);
        $this->db->from('order_product');
        if($this->input->get()){
            if($this->input->get('order_id')){
                $this->db->where('order_id', (int)$this->input->get('order_id'));
            }
            if($this->input->get('name')){
                $this->db->like('name', $this->input->get('name', true));
            }
            if($this->input->get('sku')){
                $this->db->where('sku', $this->input->get('sku', true));
            }
            if($this->input->get('brand')){
                $this->db->where('brand', $this->input->get('brand', true));
            }
            if($this->input->get('quantity')){
                $this->db->where('quantity', (int)$this->input->get('quantity'));
            }
            if($this->input->get('supplier_id')){
                $this->db->where('supplier_id', (int)$this->input->get('supplier_id'));
            }
            if($this->input->get('status_id')){
                $this->db->where('status_id', (int)$this->input->get('status_id'));
            }
        }
        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }
        $this->db->order_by('order_id', 'DESC');
        $query = $this->db->get();
        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function order_get_all($limit = false, $start = false){
        $this->db->select('SQL_CALC_FOUND_ROWS `ax_order`.*, ax_customer.login, ax_customer.balance', false);
        $this->db->from($this->table);
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
        $query = $this->db->get();
        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //customer order info
    public function order_get($id){
        $this->db->select('o.*,d.name as delivery_name, p.name as payment_name, s.name as status_name', true);
        $this->db->from('order o');
        $this->db->join('delivery_method d', 'd.id = o.delivery_method_id','left');
        $this->db->join('payment_method p', 'p.id = o.payment_method_id','left');
        $this->db->join('order_status s', 's.id = o.status','left');
        $this->db->where('o.id',(int)$id);
        if(!$this->is_admin){
            $this->db->where('o.customer_id', $this->is_login);
        }
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        
        return false;
    }
}