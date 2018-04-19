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

    public function get_products_by_customer($customer_id,$limit,$start){
        $sql = "SELECT SQL_CALC_FOUND_ROWS op.*,o.created_at,wp.ttn,wp.id as parcel_id FROM ax_order_product op
         LEFT JOIN ax_order o ON o.id=op.order_id LEFT JOIN ax_waybill_product wp2 ON op.id=wp2.order_product_id LEFT JOIN ax_waybill_parcel wp ON wp.id=wp2.waybill_parcel_id
         WHERE o.customer_id = '".$customer_id."'";

        if($this->input->get()){
            if($this->input->get('order_id')){
                $sql .= " AND op.order_id='".(int)$this->input->get('order_id')."'";
            }
            if($this->input->get('name')){
                $sql .= " AND op.name=".$this->db->escape($this->input->get('name', true))."";
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
}