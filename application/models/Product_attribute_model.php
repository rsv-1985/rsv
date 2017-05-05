<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_attribute_model extends Default_model{
    public $table = 'product_attributes';

    public function delete($product_id)
    {
        $this->db->where('product_id',(int)$product_id);
        $this->db->or_where('product_id',0);
        $this->db->delete($this->table);
    }

    public function delete_by_category($category_id){
        $this->db->where('category_id', (int)$category_id);
        $this->db->delete($this->table);
        $this->db->query("DELETE FROM ax_product_attributes WHERE product_id NOT IN (SELECT id FROM ax_product)");
    }

    public function get_filter_products_id($filters){
        if($filters){
            $sql = "SELECT
              `product_id`
            FROM
              ax_product_attributes
            WHERE ";
            $q = true;
            foreach($filters as $filter){
                if($q){
                    $sql .= "(`attribute_slug` = ".$this->db->escape($filter).")";
                }else{
                    $sql .= "  OR (`attribute_slug` = ".$this->db->escape($filter).")";
                }
                $q = false;
            }
            $sql .= "GROUP BY
              `product_id`
            HAVING
              COUNT(DISTINCT `attribute_slug`)=".count($filters);

            $query = $this->db->query($sql);

            if($query ->num_rows() > 0){
                foreach ($query ->result_array() as $item){
                    $products_id[] = $item['product_id'];
                }
                return $products_id;
            }else{
                return false;
            }
        }
    }

    public function get_attributes($category_id, $products_id = false){
        if($products_id){
            $cache = false;
        }else{
            $cache = $this->cache->file->get('attributes'.$category_id);
        }

        if (!$cache && !is_null($cache)) {
            $this->db->select('*');
            $this->db->where('category_id', (int)$category_id);
            if($products_id){
                $this->db->where_in('product_id',$products_id);
            }
            $this->db->order_by('attribute_name','ASC');
            $this->db->order_by('attribute_value','ASC');
            $this->db->group_by('attribute_slug');
            $query = $this->db->get($this->table);
            if($query->num_rows() > 0){
                $results = $query->result_array();
                $this->cache->file->save('attributes'.$category_id, $results, 604800);
                return $results;
            }
            $this->cache->file->save('attributes'.$category_id, null, 604800);
            return false;
        }else{
            return $cache;
        }

    }

    public function get_product_attributes($product_id){
        $this->db->where('product_id',(int)$product_id);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }
}