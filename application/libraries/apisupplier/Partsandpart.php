<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Доступы в конфиг файл
 * $config['api_partsandparts_login']
 * $config['api_partsandparts_password']
 * $config['api_partsandparts_currency']-код валюты обязательно должен быть в системе
 * $config['api_partsandparts_day'] - + к сроку поставки
 */
class Partsandpart{
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

        $client = new SoapClient('http://partsandparts.com/api/',array('encoding'=>'UTF-8'));

        // авторизация и получение session key
        $session_key = $client->login($this->CI->config->item('api_partsandparts_login'), $this->CI->config->item('api_partsandparts_password'));

        // поиск запчастей
        $results = $client->searchParts($session_key, $sku); // возвращает ассоциативные массивы
        if($results){
            foreach ($results as $result){
                $term = explode('-',$result['delivery']);
                $term = end($term);
                $term = $term + $this->CI->config->item('api_partsandparts_day');

                $product = [
                    'name' => $result['name'],
                    'sku' => $this->CI->product_model->clear_sku($result['article']),
                    'brand' =>  $this->CI->product_model->clear_brand($result['manufacturer']),
                    'delivery_price' => (float)$result['price'],
                    'quantity' => (int)$result['available'],
                    'supplier_id' => (int)$supplier_id,
                    'term' => $term * 24,
                    'excerpt' => $result['weight'] / 1000
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
                    'currency_id' => $this->CI->db->escape($this->CI->config->item('api_partsandparts_currency')),
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

        return  $cross_supplier;
    }
}
