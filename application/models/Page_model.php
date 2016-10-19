<?php
/**
 * Developer: Распутний Сергей Викторович
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Page_model extends Default_model{
    public $table = 'page';

    public function get_header_page($parent_id = 0){
        $cache = $this->cache->file->get('header_page');
        if(!$cache && !is_null($cache)){
            $this->db->where('status',true);
            $this->db->where('parent_id', (int)$parent_id);
            $this->db->order_by('sort', 'ASC');
            $query = $this->db->get($this->table);
            if($query->num_rows() > 0){
                $return = [];
                foreach($query->result_array() as $item){
                    $return[] = [
                        'href' => !empty($item['link']) ? $item['link'] : '/page/'.$item['slug'],
                        'target' => $item['new_window'] ? '_blank' : '_self',
                        'title' => strip_tags($item['name']),
                        'menu_title' => strip_tags($item['menu_title']),
                        'show_for_user' => $item['show_for_user'],
                        'children' => $this->get_header_page($item['id'])
                    ];
                }
                $this->cache->file->save('header_page',$return,604800);
                return $return;
            }
            $this->cache->file->save('header_page',null,604800);
            return false;
        }else{
            return $cache;
        }

    }

    public function get_footer_page(){
        $cache = $this->cache->file->get('footer_page');
        if(!$cache && !is_null($cache)){
            $this->db->where('status',true);
            $this->db->where('show_footer', true);
            $this->db->order_by('sort', 'ASC');
            $query = $this->db->get($this->table);
            if($query->num_rows() > 0){
                $return = [];
                foreach($query->result_array() as $item){
                    $return[] = [
                        'href' => !empty($item['link']) ? $item['link'] : '/page/'.$item['slug'],
                        'target' => $item['new_window'] ? '_blank' : '_self',
                        'title' => strip_tags($item['name']),
                        'menu_title' => !empty($item['menu_title']) ? strip_tags($item['menu_title']) : strip_tags($item['name'])
                    ];
                }
                $this->cache->file->save('footer_page',$return,604800);
                return $return;
            }
            $this->cache->file->save('footer_page',null,604800);
            return false;
        }else{
            return $cache;
        }

    }

    public function get_by_slug($slug){
        $this->db->where('slug', $slug);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }

    public function get_parent($id){
        $this->db->where('parent_id',(int)$id);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function get_main($id){
        $this->db->where('id',(int)$id);
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
                $return[] = base_url('page/'.$row['slug']);
            }
        }
        return $return;
    }
}
