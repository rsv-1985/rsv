<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Order_model extends Default_model{
    public $table = 'order';

    public $total_rows = 0;

    public function order_get_all_products($limit, $start){
        $this->db->select('SQL_CALC_FOUND_ROWS op.*, ip.invoice_id, o.status, c.id as customer_id, CONCAT_WS(" ", c.first_name, c.second_name) as customer_name', false);
        $this->db->from('order_product op');
        $this->db->join('order o','op.order_id=o.id', 'left');
        $this->db->join('customer c','o.customer_id=c.id', 'left');
        $this->db->join('invoice_product ip','op.id=ip.product_id', 'left');
        if($this->input->get()){
            if($this->input->get('customer_name')){
                $this->db->group_start();
                $phone = format_phone($this->input->get('customer_name'));
                if($phone){
                    $this->db->or_like('o.phone', $phone);
                }

                $this->db->or_like('o.first_name', $this->input->get('customer_name',true), 'both');
                $this->db->or_like('o.last_name',  $this->input->get('customer_name',true), 'both');
                $this->db->or_like('o.patronymic',  $this->input->get('customer_name',true), 'both');
                $this->db->or_like('o.email',  $this->input->get('customer_name',true), 'both');
                $this->db->group_end();
            }

            if($this->input->get('customer_id')){
                $this->db->where('o.customer_id', (int)$this->input->get('customer_id'));
            }

            if($this->input->get('order_id')){
                $this->db->where('o.order_id', (int)$this->input->get('order_id'));
            }
            if($this->input->get('name')){
                $this->db->like('op.name', $this->input->get('name', true));
            }
            if($this->input->get('sku')){
                $this->db->where('op.sku', $this->product_model->clear_sku($this->input->get('sku', true)));
            }
            if($this->input->get('brand')){
                $this->db->where('op.brand', $this->input->get('brand', true));
            }
            if($this->input->get('quantity')){
                $this->db->where('op.quantity', (int)$this->input->get('quantity'));
            }
            if($this->input->get('supplier_id')){
                $this->db->where_in('op.supplier_id', (array)$this->input->get('supplier_id'));
            }
            if($this->input->get('status_id')){
                $this->db->where('o.status', (int)$this->input->get('status_id'));
            }
            if($this->input->get('product_status_id')){
                $this->db->where('op.status_id', (int)$this->input->get('product_status_id'));
            }
        }
        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function order_get_all($limit = false, $start = false){
        $this->db->select('SQL_CALC_FOUND_ROWS `ax_order`.*, ax_customer.balance', false);
        $this->db->from($this->table);
        $this->db->join('customer', 'customer.id = order.customer_id', 'left');
        if($this->input->get()){
            if($this->input->get('customer_id')){
                $this->db->where('ax_order.customer_id', (int)$this->input->get('customer_id', true));
            }

            if($this->input->get('id')){
                $this->db->where('ax_order.id', (int)$this->input->get('id', true));
            }
            if($this->input->get('first_name')){
                $this->db->like('ax_order.first_name', $this->input->get('first_name', true));
            }
            if($this->input->get('last_name')){
                $this->db->like('ax_order.last_name', $this->input->get('last_name', true));
            }
            if($this->input->get('email')){
                $this->db->like('ax_order.email', $this->input->get('email', true));
            }
            if($this->input->get('telephone')){
                $this->db->like('ax_order.telephone', $this->input->get('telephone', true));
            }
            if($this->input->get('delivery_method_id')){
                $this->db->where('ax_order.delivery_method_id', (int)$this->input->get('delivery_method_id', true));
            }
            if($this->input->get('payment_method_id')){
                $this->db->where('ax_order.payment_method_id', (int)$this->input->get('payment_method_id', true));
            }
            if($this->input->get('status')){
                $this->db->where('ax_order.status', (int)$this->input->get('status', true));
            }
        }
        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }
        $this->db->order_by('ax_order.id', 'DESC');
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
            $result = $query->row_array();
            if($result['payment_name'] == ''){
                $result['payment_name'] = 'С личного баланса';
            }
            return $result;
        }
        
        return false;
    }

    public function get_products_status($order_id){
        $this->db->where('order_id',(int)$order_id);
        $this->db->select('os.name,os.color');
        $this->db->from('order_product op');
        $this->db->join('order_status os','os.id=op.status_id');
        $this->db->where('op.order_id',(int)$order_id);
        $this->db->group_by('op.status_id');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function getSubtotal($id){

        $sql = "SELECT SUM(op.quantity*op.price) as sub_total FROM ax_order_product op WHERE op.order_id = '".(int)$id."'";

        $return_order_statuses = $this->orderstatus_model->get_return();

        if($return_order_statuses){
            $sql .= " AND op.status_id NOT IN ('".implode("','",$return_order_statuses)."')";
        }

        return $this->db->query($sql)->row_array()['sub_total'];
    }
}