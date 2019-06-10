<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute_model extends Default_model
{
    public $table = 'attribute';

    public function getValues($attribute_id){
        $this->db->from('attribute_value');
        $this->db->where('attribute_id', (int)$attribute_id);
        $this->db->order_by('sort_order','ASC');
        return $this->db->get()->result_array();
    }

    public function deleteValues($attribute_id){
        $this->db->where('attribute_id', (int)$attribute_id);
        $this->db->delete('attribute_value');
    }

    public function addValue($data){
        $this->db->insert('attribute_value',$data);
    }

    public function deleteAttr($attribute_id){
        $this->db->where('attribute_id', (int)$attribute_id);
        $this->db->delete('product_attribute');

        $this->db->where('attribute_id', (int)$attribute_id);
        $this->db->delete('attribute_value');

        $this->db->where('id', (int)$attribute_id);
        $this->db->delete('attribute');
    }

    public function getAttributes(){
        return $this->db->order_by('name', 'ASC')->get('attribute')->result_array();
    }

}