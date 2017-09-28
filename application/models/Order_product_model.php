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
        $this->db->join('order_status s', 's.id = p.status_id');
        $this->db->where('order_id',(int)$id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function get_products_by_customer($customer_id,$limit,$start){
        $this->db->select('SQL_CALC_FOUND_ROWS op.*,o.created_at,wp.ttn', false);
        $this->db->from('order_product op');
        $this->db->join('order o', 'o.id=op.order_id');
        $this->db->join('waybill_product wp2','op.id=wp2.order_product_id','left');
        $this->db->join('waybill_parcel wp','wp.id=wp2.waybill_parcel_id','left');
        if($this->input->get()){
            if($this->input->get('order_id')){
                $this->db->where('op.order_id', (int)$this->input->get('order_id'));
            }
            if($this->input->get('name')){
                $this->db->like('op.name', $this->input->get('name', true));
            }
            if($this->input->get('sku')){
                $this->load->model('product_model');
                $this->db->where('op.sku', $this->product_model->clear_sku($this->input->get('sku', true)));
            }
            if($this->input->get('brand')){
                $this->db->where('op.brand', $this->input->get('brand', true));
            }
            if($this->input->get('quantity')){
                $this->db->where('op.quantity', (int)$this->input->get('quantity'));
            }
            if($this->input->get('status_id')){
                $this->db->where('op.status_id', (int)$this->input->get('status_id'));
            }
        }
        $this->db->where('o.customer_id',(int)$customer_id);

        $this->db->limit((int)$limit, (int)$start);

        $this->db->order_by('op.order_id', 'DESC');

        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
}