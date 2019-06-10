<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mproduct extends Default_model{

    public function getAttributes($product_id){
        $this->db->from('product_attribute pa');
        $this->db->select('pa.*, a.name as attribute_name, av.value as attribute_value');
        $this->db->join('attribute a','a.id=pa.attribute_id');
        $this->db->join('attribute_value av','av.id=pa.attribute_value_id');
        $this->db->where('product_id',(int)$product_id);
        return $this->db->get()->result_array();
    }

    public function deleteAttributes($product_id){
        $this->db->where('product_id', (int)$product_id);
        $this->db->delete('product_attribute');
    }

    public function deleteImages($product_id){
        $this->db->where('product_id', (int)$product_id);
        $this->db->delete('product_images');
    }

    public function addAttributesBatch($data){
        $this->db->insert_batch('product_attribute', $data);
    }
}