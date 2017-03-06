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

    public function clear_importtmp(){
        $this->db->where('sku', null);
        $this->db->or_where('brand', null);
        $this->db->or_where('delivery_price', 0);
        $this->db->or_where('quantity', 0);
        $this->db->delete($this->table);
    }
    
    public function import_get_all($id, $limit){
        $return = false;
        $this->db->limit($limit);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $return = $query->result_array();
            $id = [];
            foreach ($return as $item){
                $id[] = $item['id'];
            }
            $this->db->where_in('id',$id);
            $this->db->delete('importtmp');
            unset($id);
        }
        return $return;
    }
}