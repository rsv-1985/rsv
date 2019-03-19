<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_attribute_model extends Default_model
{
    public $table = 'product_attributes';

    public function delete($product_id)
    {
        $this->db->where('product_id', (int)$product_id);
        $this->db->or_where('product_id', 0);
        $this->db->delete($this->table);
    }

    public function delete_by_category($category_id)
    {
        $this->db->where('category_id', (int)$category_id);
        $this->db->delete($this->table);
    }

    public function delete_by_name($attribute_name)
    {
        $this->db->where('attribute_name', $attribute_name);
        $this->db->delete($this->table);
    }

    public function get_filter_products_id($filters)
    {
        if ($filters) {
            $sql = "SELECT
              `product_id`
            FROM
              ax_product_attributes
            WHERE ";
            $q = true;
            foreach ($filters as $filter) {
                if ($q) {
                    $sql .= "(`attribute_slug` = " . $this->db->escape($filter) . ")";
                } else {
                    $sql .= "  OR (`attribute_slug` = " . $this->db->escape($filter) . ")";
                }
                $q = false;
            }
            $sql .= "GROUP BY
              `product_id`
            HAVING
              COUNT(DISTINCT `attribute_slug`)=" . count($filters);

            $query = $this->db->query($sql);

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $item) {
                    $products_id[] = $item['product_id'];
                }
                return $products_id;
            } else {
                return false;
            }
        }
    }

    public function get_possible_attributes($category_id, $filter_data = []){

        if($filter_data){
            $sql = "SELECT DISTINCT pa.attribute_value_id FROM ax_product_attribute pa 
            INNER JOIN (SELECT id FROM ax_product";

            if(isset($filter_data['attr'])){
                $SQL = "(SELECT product_id FROM ax_product_attribute WHERE ";
                $where = [];
                $count = 0;
                foreach ($filter_data['attr'] as $attr_id => $values){
                    $count += count($values);
                    $where[] = "(attribute_id = ".(int)$attr_id." AND attribute_value_id IN (".implode(',',$values)."))";
                }
                $SQL .= implode(' OR ',$where);
                $SQL .= " group by product_id ) pa2";
                $sql .= " INNER JOIN ".$SQL." ON pa2.product_id=id";
            }

            $sql .=" WHERE category_id = '".(int)$category_id."'";

            if(isset($filter_data['brand'])){
                $sql .= " AND brand IN ('".implode("','",$filter_data['brand'])."')";
            }


            $sql .=") p ON p.id = pa.product_id";

            $results = $this->db->query($sql)->result_array();
            if($results){
                $return = [];
                foreach ($results as $result){
                    $return[] = $result['attribute_value_id'];
                }

                return $return;
            }

        }

        return false;
    }

    public function get_attributes($category_id)
    {
        $sql = " SELECT
          a.in_filter,
          a.max_height,
          a.name as attribute, 
          av.value as attribute_value, 
          pa.attribute_value_id, 
          pa.attribute_id  
          FROM ax_product_attribute pa 
          INNER JOIN ax_attribute a ON a.id = pa.attribute_id
          INNER JOIN ax_attribute_value av ON av.id = pa.attribute_value_id
          INNER JOIN ax_product p ON p.id = pa.product_id
          WHERE p.category_id = '".(int)$category_id."' AND a.in_filter = 1
          GROUP BY pa.attribute_value_id 
          ORDER BY av.sort_order ASC, a.sort_order ASC
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return false;
    }

    public function get_product_attributes($product_id)
    {
        $this->db->where('product_id', (int)$product_id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}