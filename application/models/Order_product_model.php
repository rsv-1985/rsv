<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Order_product_model extends Default_model{
    public $table = 'order_product';
    public $total_rows = 0;

    public function delete_by_order($id){
        $this->db->where('order_id', (int)$id);
        $this->db->delete($this->table);
    }
    //customer order product
    public function product_get($id){
        $this->db->select('p.*, s.name as status_name');
        $this->db->from('order_product p');
        $this->db->join('order_status s', 's.id = p.status_id','left');
        $this->db->where('order_id',(int)$id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function getProducts($order_id){
        $sql = "SELECT op.*, ip.invoice_id FROM ax_order_product op 
        LEFT JOIN ax_invoice_product ip ON op.id = ip.product_id WHERE op.order_id = '".(int)$order_id."' ORDER BY op.id ASC";
        return $this->db->query($sql)->result_array();
    }

    public function get_products_by_customer($customer_id,$limit,$start){
        $sql = "SELECT SQL_CALC_FOUND_ROWS op.*,o.created_at FROM ax_order_product op
         LEFT JOIN ax_order o ON o.id=op.order_id
         WHERE o.customer_id = '".$customer_id."'";

        if($this->input->get()){
            if($this->input->get('order_id')){
                $sql .= " AND op.order_id='".(int)$this->input->get('order_id')."'";
            }

            if($this->input->get('sku')){
                $this->load->model('product_model');
                $sql .= " AND op.sku=".$this->db->escape($this->product_model->clear_sku($this->input->get('sku', true)))."";
            }

            if($this->input->get('brand')){
                $this->load->model('product_model');
                $sql .= " AND op.brand=".$this->db->escape($this->product_model->clear_brand($this->input->get('brand', true)))."";
            }

            if($this->input->get('quantity')){
                $sql .= " AND op.quantity='".(int)$this->input->get('quantity')."'";
            }

            if($this->input->get('status_id')){
                $sql .= " AND op.status_id='".(int)$this->input->get('status_id')."'";
            }
        }
        $sql .= " ORDER BY op.order_id DESC";
        $sql .= " LIMIT ".(int)$start.", ".(int)$limit;




        $query = $this->db->query($sql);

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function get_status_totals($statuses, $customer_id = false){
        $return = [];
        if($statuses){
            foreach ($statuses as $status_id => $value){
                $sql = "SELECT SUM(op.price * op.quantity) as total, SUM(op.quantity) as qty FROM ax_order_product op 
                LEFT JOIN ax_order o ON o.id = op.order_id
                LEFT JOIN ax_customer c ON c.id = o.customer_id WHERE op.status_id ='".(int)$status_id."'";
                if($customer_id){
                    $sql .= " AND o.customer_id = '".(int)$customer_id."'";
                }

                $res = $this->db->query($sql)->row_array();
                if($res){
                    $return[$status_id] = $res;
                }else{
                    $return[$status_id] = [
                        'total' => '0',
                        'qty' => '0'
                    ];
                }
            }

           return $return;
        }

        return false;
    }
}