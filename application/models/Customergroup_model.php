<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customergroup_model extends Default_model{
    public $table = 'customer_group';
    public $customer_group;

    public function __construct()
    {
        $this->customer_group = $this->get_customer_group();
    }

    public function get_customer_group(){
        if (@$this->is_login) {
            return $this->get($this->session->customer_group_id);
        } else {
            return $this->get_unregistered();
        }
    }

    public function get_default(){
        $this->db->where('is_default', true);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array()['id'];
        }

        return false;
    }

    public function get_types(){
        return [
            '+' => lang('text_margin'),
            '-' => lang('text_discount')
        ];
    }

    public function update($field, $value){
        $this->db->update($this->table,array($field => $value));
    }

    public function get_group(){
        $return = false;
        $results = $this->get_all();
        if($results){
            $return = [];
            foreach($results as $result){
                $return[$result['id']] = $result;
            }
        }
        return $return;
    }

    public function get_unregistered(){
        $this->db->where('is_unregistered',true);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }
}