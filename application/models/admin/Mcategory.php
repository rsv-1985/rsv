<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mcategory extends Default_model{
    public $total_rows = 0;

    public function getCategoriesWithPath(){

    }

    public function getCategories($limit = false, $start = false){
        $this->db->select('SQL_CALC_FOUND_ROWS *', false);
        $this->db->from('category c');
        if($this->input->get('id')){
            $this->db->where('c.id',(int)$this->input->get('id'));
        }
        if($this->input->get('name')){
            $this->db->like('c.name',$this->input->get('name',true), 'both');
        }
        if(isset($_GET['status']) && !empty($_GET['status'])){
            $this->db->where('c.status',(int)$this->input->get('status'));
        }

        $this->db->order_by('parent_id', 'ASC');
        $this->db->order_by('id', 'ASC');
        if($limit){
            $this->db->limit((int)$limit, (int)$start);
        }


        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if ($query->num_rows() > 0) {
            return $query->custom_result_object('mcategory');
        }
        return false;
    }

    public function getPath($parent_id){
        $this->db->where('id',(int)$parent_id);
        $parent_info = $this->db->get('category')->row_array();

        if($parent_info){
            $breadcrumb[] = $parent_info['name'];
            if($parent_info['parent_id']){
                $breadcrumb[] = $this->getPath($parent_info['parent_id']);
            }
        }

        if(@$breadcrumb){
            return @implode(' > ',array_reverse($breadcrumb));
        }

    }
}