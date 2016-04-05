<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_model extends Default_model
{
    public $table = 'banner';

    public function get_slider(){
        $this->db->where('show_slider', true);
        $this->db->where('status', true);
        $this->db->order_by('sort', 'asc');
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function get_box(){
        $this->db->where('show_box', true);
        $this->db->where('status', true);
        $this->db->order_by('sort', 'asc');
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function get_carousel(){
        $this->db->where('show_carousel', true);
        $this->db->where('status', true);
        $this->db->order_by('sort', 'asc');
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function get_product(){
        $this->db->where('show_product', true);
        $this->db->where('status', true);
        $this->db->order_by('sort', 'asc');
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }
}