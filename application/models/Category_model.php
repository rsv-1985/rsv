<?php

/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
class Category_model extends Default_model
{
    public $table = 'category';

    public $total_rows = 0;

    public function cat_get_all($limit, $start){
        $this->db->select('SQL_CALC_FOUND_ROWS *', false);
        if($this->input->get('id')){
            $this->db->where('id',(int)$this->input->get('id'));
        }
        if($this->input->get('name')){
            $this->db->like('name',$this->input->get('name',true), 'both');
        }
        if(isset($_GET['status']) && !empty($_GET['status'])){
            $this->db->where('status',(int)$this->input->get('status'));
        }

        if ($limit && $start) {
            $this->db->limit((int)$limit, (int)$start);
        } elseif ($limit) {
            $this->db->limit((int)$limit);
        }

        $query = $this->db->get($this->table);

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function admin_category_get_all()
    {
        $query = $this->db->get($this->table);
        if ($query->num_rows()) {
            $return = [];
            foreach ($query->result_array() as $cat) {
                $return[$cat['id']] = $cat;
            }
            return $return;
        }
        return false;
    }

    public function category_get_all()
    {
        $cats = [];

        $settings_tecdoc_tree = $this->settings_model->get_by_key('tecdoc_tree');
        if($settings_tecdoc_tree){
            $tecdoc_tree = [];

            $tecdoc_tree_full = $this->tecdoc->getTreeFull();
            if($tecdoc_tree_full){
                foreach ($tecdoc_tree_full as $item){
                    if(isset($settings_tecdoc_tree[$item->ID_tree]) && @$settings_tecdoc_tree[$item->ID_tree]['home']){
                        $tecdoc_tree[] = [
                            'id' => $item->ID_tree,
                            'parent_id' => 0,
                            'name' => $settings_tecdoc_tree[$item->ID_tree]['name'] ?  $settings_tecdoc_tree[$item->ID_tree]['name'] : $item->Name,
                            'slug' => $item->ID_tree,
                            'tecdoc' => true,
                            'image' => $settings_tecdoc_tree[$item->ID_tree]['logo']
                        ];
                    }
                    if($item->ID_tree != '10001' && isset($settings_tecdoc_tree[$item->ID_tree]) && !@$settings_tecdoc_tree[$item->ID_tree]['hide']){
                        $tecdoc_tree[] = [
                            'id' => $item->ID_tree,
                            'parent_id' => $item->ID_parent,
                            'name' => $settings_tecdoc_tree[$item->ID_tree]['name'] ?  $settings_tecdoc_tree[$item->ID_tree]['name'] : $item->Name,
                            'slug' => $item->ID_tree,
                            'tecdoc' => true,
                            'image' => $settings_tecdoc_tree[$item->ID_tree]['logo']
                        ];
                    }

                }
            }

            if($tecdoc_tree){
                foreach ($tecdoc_tree as $cat) {
                    $cats_ID[$cat['id']][] = $cat;
                    $cats[$cat['parent_id']][$cat['id']] = $cat;
                }
            }
        }
        $this->db->where('status', true);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $cat) {
                $cats_ID[$cat['id']][] = $cat;
                $cats[$cat['parent_id']][$cat['id']] = $cat;
            }
        }

        if(count($cats)){
            return $this->build_tree($cats, 0);
        }
    }

    public function build_tree($cats, $parent_id, $sub = false)
    {
        if($sub){
            $tree = '<li class="nav_catalog_has_dd">';
        }else{
            $tree = '<li>';
        }

        if(isset($cats[$parent_id])){
            foreach ($cats[$parent_id] as $cat){
                if(isset($cats[$cat['id']])){
                    $tree .= '<li class="nav_catalog_has_dd"><a href="#">' . $cat['name'].'</a><ul>';
                    $tree .= $this->build_tree($cats,$cat['id'],true);
                    if(@$cat['image']){
                        $tree .= '<img class="catalog_nav_img " src="'.$cat['image'].'">';
                    }
                    $tree .= '</ul></li>';
                }else{
                    if(@$cat['tecdoc']){
                        $tree .= '<li><a href="/catalog/?id_tree='.$cat['slug'].'">' . $cat['name'].'</a></li>';
                    }else{
                        $tree .= '<li><a href="/category/'.$cat['slug'].'">' . $cat['name'].'</a></li>';
                    }

                }
            }
        }

        $tree .= '</li>';

        return $tree;
    }

    public function get_by_slug($slug)
    {
        $this->db->where('slug', $slug);
        $this->db->where('status', true);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    public function get_brands($id, $limit = false)
    {
        $cache = $this->cache->file->get('category_brands' . $id);
        if (!$cache && !is_null($cache)) {
            $this->db->select('brand');
            $this->db->join('product', 'product.id=product_price.product_id');
            $this->db->where('category_id', (int)$id);
            $this->db->where('brand !=', '');
            if ($limit) {
                $this->db->limit((int)$limit);
            }
            $this->db->order_by('brand', 'ASC');
            $this->db->group_by('brand');
            $query = $this->db->get('product_price');

            if ($query->num_rows() > 0) {
                $brands = [];
                foreach ($query->result_array() as $item) {
                    $brands[url_title($item['brand'])] = $item['brand'];
                }
                $this->cache->file->save('category_brands' . $id, $brands, 604800);
                return $brands;
            }
            $this->cache->file->save('category_brands' . $id, null, 604800);
            return false;
        } else {
            return $cache;
        }

    }

    public function get_sitemap()
    {
        $return = false;
        $this->db->select(['slug', 'updated_at']);
        $this->db->where('status', true);
        $this->db->from($this->table);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $return = [];
            foreach ($query->result_array() as $row) {
                $return[] = [
                    'url' => base_url('category/' . $row['slug']),
                    'updated_at' => $row['updated_at']
                ];
            }
        }
        return $return;
    }
}