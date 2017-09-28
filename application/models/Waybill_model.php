<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Waybill_model extends Default_model{
    public $table = 'waybill';

    public $statuses;

    public function __construct()
    {
        parent::__construct();
        $this->statuses = [
            0 => lang('text_status_0'),
            1 => lang('text_status_1')
        ];
    }

    public function delete($id){
        $this->db->query("DELETE FROM ax_waybill_product WHERE waybill_parcel_id IN (SELECT id FROM ax_waybill_parcel WHERE waybill_id = '".(int)$id."')");
        $this->db->query("DELETE FROM ax_waybill_parcel WHERE waybill_id ='".(int)$id."'");
        $this->db->query("DELETE FROM ax_waybill WHERE id ='".(int)$id."'");
    }

    public function get_orders(){
        $sql = "SELECT * FROM ax_order o WHERE 1";
        if($this->input->get('delivery_method_id')){
            $sql .= " AND o.delivery_method_id = '".(int)$this->input->get('delivery_method_id')."'";
        }
        if(isset($_GET['paid']) && $_GET['paid'] != ''){
            $sql .= " AND o.paid = '".(int)$this->input->get('paid')."'";
        }
        $sql .= " AND (SELECT count(product_id) FROM ax_order_product op WHERE op.order_id=o.id AND op.status_id = '".(int)$this->input->get('status_id')."') > 0 ORDER BY o.customer_id DESC, o.delivery_method_id ASC";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return $query->result_array();
        }

        return false;
    }

    public function get_products($order_id, $status_id){
        $sql = "SELECT op.id, op.name,op.sku,op.brand,op.quantity,s.name as sname
        FROM ax_order_product op 
        LEFT JOIN ax_supplier s ON s.id=op.supplier_id
        WHERE op.order_id = '".(int)$order_id."'
        AND op.status_id = '".(int)$status_id."'
        AND op.id NOT IN (SELECT order_product_id FROM ax_waybill_product)";

        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function get_parcel_by_customer($data){
        $this->db->where('first_name',$data['first_name']);
        $this->db->where('last_name',$data['last_name']);
        $this->db->where('patronymic',$data['patronymic']);
        $this->db->where('delivery_method_id', $data['delivery_method_id']);
        $this->db->where('address',$data['address']);
        $this->db->where('waybill_id',(int)$data['waybill_id']);
        $query = $this->db->get('waybill_parcel');
        if($query->num_rows() > 0){
            return $query->row_array()['id'];
        }
        return false;
    }

    public function insert_parcel($data, $id = false){
        if($id){
            $this->db->where('id',(int)$id);
            $this->db->update('waybill_parcel', $data);
            return $id;
        }else{
            $this->db->insert('waybill_parcel', $data);
            return $this->db->insert_id();
        }

    }

    public function insert_product_batch($data){
        $this->db->insert_batch('waybill_product', $data);
    }

    public function get_parcels($waybill_id){
        $parcels = false;
        $this->db->where('waybill_id',(int)$waybill_id);
        $query = $this->db->get('waybill_parcel');
        if($query->num_rows() > 0){
            $results = $query->result_array();
            foreach ($results as $item){
                $parcels[$item['id']] = $item;
                $parcels[$item['id']]['products'] =  $this->get_parcel_products($item['id']);;
            }
        }
        return $parcels;
    }

    public function get_parcel_products($waybill_parcel_id)
    {
        $sql = "SELECT wp.id, wp.order_product_id, op.sku, op.brand, op.name,op.quantity,s.name as sname FROM ax_waybill_product wp
        LEFT JOIN ax_order_product op ON op.id = wp.order_product_id
        LEFT JOIN ax_supplier s ON s.id = op.supplier_id
        WHERE wp.waybill_parcel_id = '".(int)$waybill_parcel_id."'";


        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function delete_waybill_product($id){
        $this->db->where('id',(int)$id);
        $this->db->delete('waybill_product');
    }

    public function delete_waybill_parcel($id){
        $this->db->where('id',(int)$id);
        $this->db->delete('waybill_parcel');

        $this->db->where('waybill_parcel_id',(int)$id);
        $this->db->delete('waybill_product');
    }

    public function set_status_order_product($waybill_id,$status_id){
        $sql = "UPDATE ax_order_product SET status_id = '".(int)$status_id."' WHERE id IN (SELECT wp.order_product_id FROM ax_waybill_product wp
        LEFT JOIN ax_waybill_parcel wp2 ON wp2.id = wp.waybill_parcel_id
        LEFT JOIN ax_waybill w ON w.id = wp2.waybill_id
        WHERE w.id = '".(int)$waybill_id."')";
        $this->db->query($sql);
    }
}