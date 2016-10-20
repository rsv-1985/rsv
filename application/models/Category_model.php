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

    public function category_get_all($parent_id = 0)
    {
        $this->db->where('status', true);
        $this->db->where('parent_id', (int)$parent_id);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            $category = [];
            foreach ($query->result_array() as $cat) {
                if ($parent_id == 0) {
                    $cat['brands'] = false;
                } else {
                    $cat['brands'] = $this->get_brends($cat['id'], 10);
                }

                $cat['children'] = $this->category_get_all($cat['id']);
                $category[] = $cat;
            }

            return $category;
        }
        return false;
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
        $cache = $this->cache->file->get('category_brands_' . $id . '_limit_' . $limit);
        if (!$cache && !is_null($cache)) {
            $this->db->distinct();
            $this->db->select('brand');
            $this->db->join('product', 'product.id=product_price.product_id');
            $this->db->where('category_id', (int)$id);
            $this->db->where('brand !=', '');
            $this->db->where('status', true);
            if ($limit) {
                $this->db->limit((int)$limit);
            }
            $this->db->order_by('brand', 'ASC');
            $query = $this->db->get('product_price');

            if ($query->num_rows() > 0) {
                $this->cache->file->save('category_brands_' . $id . '_limit_' . $limit, $query->result_array(), 604800);
                return $query->result_array();
            }
            $this->cache->file->save('category_brands_' . $id . '_limit_' . $limit, null, 604800);
            return false;
        } else {
            return $cache;
        }

    }

    public function get_sitemap()
    {
        $return = false;
        $this->db->select('slug');
        $this->db->where('status', true);
        $this->db->from($this->table);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $return = [];
            foreach ($query->result_array() as $row) {
                $return[] = base_url('category/' . $row['slug']);
            }
        }
        return $return;
    }
}