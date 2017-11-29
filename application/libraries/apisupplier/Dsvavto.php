<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Dsvavto{
    public $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('product_model');
    }

    public function get_search($supplier_id, $sku, $brand, $search_data){
        $cross_supplier = [];
        //Удаляем все ценовые предложения перед поиском
        $this->CI->product_model->product_delete(['supplier_id' => (int)$supplier_id, 'updated_at <' => date('Y-m-d H:i:s', strtotime('- 1 day'))]);
        $content = file_get_contents('http://dsv-avto.com.ua/webservice/getGroups/?login=partz.ua@gmail.com&pass=partz.ua@gmail.com&article='.$sku);
        if($content){
            $api_brands = json_decode($content);
            if($brand){
                foreach ($api_brands as $api_brand){
                    if($api_brand->BRAND == $brand){
                        $group = $api_brand->GROUP;
                        break;
                    }
                }
            }else{
                $group = $api_brands[0]->GROUP;
            }
            $parts = file_get_contents('http://dsv-avto.com.ua/webservice/getArticles/?login=partz.ua@gmail.com&pass=partz.ua@gmail.com&group=='.$group);
            if($parts){
                $results = json_decode($parts);
                foreach ($results as $result){
                    $term = explode('-',$result->DAYsDELIVERY);
                    $term = end($term);
                    $term = (int)$term * 24;
                    $product = [
                        'name' => $result->DESCR,
                        'sku' => $this->CI->product_model->clear_sku($result->ARTICLE),
                        'brand' =>  $this->CI->product_model->clear_brand($result->BRAND),
                        'delivery_price' => (float)$result->PRICE,
                        'quantity' => (int)$result->BOX,
                        'supplier_id' => (int)$supplier_id,
                        'term' => $term,
                        'excerpt' => ''
                    ];

                    if($product['sku'] != $sku || $product['brand'] != $brand){
                        $cross_supplier[] = [
                            'sku' => $product['sku'],
                            'brand' => $product['brand']
                        ];
                    }

                    $product_data = [
                        'sku' => $product['sku'],
                        'brand' => $product['brand'],
                        'name' => $product['name'],
                        'slug' => $this->CI->product_model->getSlug($product),
                    ];

                    $product_id = $this->CI->product_model->product_insert($product_data, false, false);

                    $price_data[] = [
                        'product_id' => $this->CI->db->escape($product_id),
                        'excerpt' => $this->CI->db->escape($product['excerpt']),
                        'currency_id' => $this->CI->db->escape(4),
                        'delivery_price' => $this->CI->db->escape($product['delivery_price']),
                        'saleprice' => $this->CI->db->escape(0),
                        'quantity' => $this->CI->db->escape($product['quantity']),
                        'supplier_id' => $this->CI->db->escape($supplier_id),
                        'term' => $this->CI->db->escape($product['term']),
                        'created_at' => $this->CI->db->escape(date("Y-m-d H:i:s")),
                        'updated_at' => $this->CI->db->escape(date("Y-m-d H:i:s")),
                    ];
                }

                if(@$price_data){
                    $this->CI->product_model->price_insert($price_data);
                }
            }
        }
        return  $cross_supplier;
    }
}
