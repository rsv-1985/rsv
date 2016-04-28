<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Category_model extends Default_model{
    public $table = 'category';

    public function admin_category_get_all(){
        $query = $this->db->get($this->table);
        if($query->num_rows()){
            $return = [];
            foreach ($query->result_array() as $cat){
                $return[$cat['id']] = $cat;
            }
            return $return;
        }
        return false;
    }
    
    public function category_get_all($parent_id = 0){

        $this->db->where('status', true);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $cats = [];
            foreach ($query->result_array() as $cat){
                $cat['brands'] = false;
                $this->db->distinct();
                $this->db->select('brand');
                $this->db->where('category_id', $cat['id']);
                $this->db->limit(20);
               
                $query = $this->db->get('product');
                
                if($query->num_rows() > 0){
                    $cat['brands'] = $query->result_array();
                }
                $cats[$cat['parent_id']][$cat['id']] =  $cat;
            }
            return $cats;
        }
        return false;
    }

    public function get_by_slug($slug){
        $this->db->where('slug', $slug);
        $this->db->where('status', true);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }
    
    public function get_brends($id){
        $this->db->distinct();
        $this->db->select('brand');
        $this->db->where('category_id', (int)$id);
        $this->db->where('brand !=', '');
        $this->db->limit(500);
        $this->db->order_by('brand', 'ASC');
        $query = $this->db->get('product');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function get_sitemap(){
        $return = false;
        $this->db->select('slug');
        $this->db->from($this->table);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $return = [];
            foreach($query->result_array() as $row){
                $return[] = base_url('category/'.$row['slug']);
            }
        }
        return $return;
    }
}