<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Brand_group_model extends Default_model{
    public $table = 'group_brand';

    public function delete($id){
        $this->deleteBrands($id);
        $this->db->where('id',(int)$id);
        $this->db->delete($this->table);
    }

    public function getBrandGroupByBrand($brand){
        $sql = "SELECT * FROM ax_group_brand WHERE id IN(SELECT group_brand_id FROM ax_group_brand_item WHERE brand = ".$this->db->escape($brand).")";
        $query = $this->db->query($sql);
        if($query->num_rows()){
            return $query->row_array();
        }
        return false;
    }

    public function getBrands($id_group){
        $this->db->where('group_brand_id',(int)$id_group);
        $query = $this->db->get('group_brand_item');
        if($query->num_rows()){
            return $query->result_array();
        }
        return false;
    }

    public function deleteBrands($id_group){
        $this->db->where('group_brand_id',(int)$id_group);
        $this->db->delete('group_brand_item');
    }

    public function addBrand($data){
        $this->db->insert('group_brand_item', $data);
    }
}