<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Category_model extends Default_model{
    public $table = 'category';

    public function category_get_all(){
        $this->db->where('status', true);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return $query->result_array();
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