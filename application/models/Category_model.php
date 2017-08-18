<?php

/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
class Category_model extends Default_model
{
    public $table = 'category';

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
        $this->db->where('status', true);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $cat) {
                $cats_ID[$cat['id']][] = $cat;
                $cats[$cat['parent_id']][$cat['id']] = $cat;
            }
            return $this->build_tree($cats, 0);
        }
        return false;
    }

    public function build_tree($cats, $parent_id, $sub = false)
    {
        if($sub){
            $tree = '<ul class="nav tree">';
        }else{
            $tree = '<ul class="nav">';
        }


        if(isset($cats[$parent_id])){
            foreach ($cats[$parent_id] as $cat){
                if(isset($cats[$cat['id']])){
                    $tree .= '<li><a class="tree-toggle">' . $cat['name'].'<span class="caret pull-right"></a></b>';
                    $tree .= $this->build_tree($cats,$cat['id'],true);
                    $tree .= '</li>';
                }else{
                    $tree .= '<li><a href="/category/'.$cat['slug'].'">' . $cat['name'].'</a></li>';
                }
            }
        }
        $tree .= '</ul>';
        /*
        if (is_array($cats) and isset($cats[$parent_id])) {
            $tree = '<ul>';
            if ($only_parent == false) {
                foreach ($cats[$parent_id] as $cat) {
                    $tree .= '<li><a href="/category/'.$cat['slug'].'">' . $cat['name'].'</a>';
                    $tree .= $this->build_tree($cats, $cat['id']);
                    $tree .= '</li>';
                }
            } elseif (is_numeric($only_parent)) {
                $cat = $cats[$parent_id][$only_parent];
                $tree .= '<li>' . $cat['name'];
                $tree .= $this->build_tree($cats, $cat['id']);
                $tree .= '</li>';
            }
            $tree .= '</ul>';
        } else return null;
        */
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

    public function get_brends($id, $limit = false)
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