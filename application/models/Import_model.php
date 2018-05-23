<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Import_model extends Default_model
{
    public $table = 'importtmp';

    public $total_rows = 0;

    public function clear_importtmp(){
        $this->db->where('sku', null);
        $this->db->or_where('brand', null);
        $this->db->or_where('delivery_price', 0);
        $this->db->or_where('quantity', 0);
        $this->db->delete($this->table);
    }

    public function check_get_all($id){
        $return = false;
        $this->db->limit(10);
        $this->db->where('id >',$id);
        $this->db->order_by('id','ASC');
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $return = $query->result_array();
        }
        return $return;
    }
    
    public function import_get_all(){
        $sql = "SELECT i.*,p.id as product_id FROM ax_importtmp i LEFT JOIN ax_product p ON p.sku = i.sku AND p.brand=i.brand   LIMIT 1000";

        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function import_delete($ids)
    {
        $this->db->where_in('id',$ids);
        $this->db->delete('importtmp');
    }

    public function get_importtmp($limit = false, $start = false){
        $this->db->from('importtmp');
        $this->db->select('SQL_CALC_FOUND_ROWS *', false);
        $this->db->limit((int)$limit, (int)$start);
        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}