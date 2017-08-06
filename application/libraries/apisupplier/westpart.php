<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Доступы в конфиг файл
 * $config['api_westpart_login']
 * $config['api_westpart_password']
 * $config['api_westpart_currency']-код валюты обязательно должен быть в системе
 * $config['api_westpart_plus_day'] - + к сроку поставки
 */
class Westpart{
    public $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('product_model');
    }

    public function get_search($supplier_id, $sku, $brand, $search_data){
        $cross_supplier = [];
        if($brand){
            $url = 'https://westpart.com.ua/ws/?login='.$this->CI->config->item('api_westpart_login').
                '&password='.$this->CI->config->item('api_westpart_password').
                '&partnumber='.$sku.
                '&manufacturer='.$this->CI->config->item($sku);
        }else{
            $url = 'https://westpart.com.ua/ws/?login='.$this->CI->config->item('api_westpart_login').
                '&password='.$this->CI->config->item('api_westpart_password').
                '&partnumber='.$sku;
        }

        $xml = simplexml_load_string(file_get_contents($url));
        $json = json_encode($xml);
        $results = json_decode($json,TRUE);
        if($results['Status']['count'] > 0){
            foreach ($results['Result']['Item'] as $result){
                if($result['Price'] == 0 || $result['Quantity']){
                    continue;
                }
                $product = [
                    'name' => $result['PartName'],
                    'sku' => $this->CI->product_model->clear_sku($result['PartCleanNumber']),
                    'brand' =>  $this->CI->product_model->clear_brand($result['Manufacturer']),
                    'delivery_price' => (float)$result['Price'],
                    'quantity' => (int)$result['Quantity'],
                    'supplier_id' => (int)$supplier_id,
                    'term' => ($result['DeliveryDays'] + $this->CI->config->item('api_westpart_plus_day')) * 24,
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
                    'currency_id' => $this->CI->db->escape($this->CI->config->item('api_westpart_currency')),
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

        return $cross_supplier;
    }
}