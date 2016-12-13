<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class News_model extends Default_model{
    public $table = 'news';

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
        $this->db->select(['slug','updated_at']);
        $this->db->where('status', true);
        $this->db->from($this->table);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $return = [];
            foreach($query->result_array() as $row){
                $return[] = [
                    'url' => base_url('news/'.$row['slug']),
                    'updated_at' => $row['updated_at']
                ];
            }
        }
        return $return;
    }
}