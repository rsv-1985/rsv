<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Доступы в конфиг файл
 * $config['api_jpauto_login']
 * $config['api_jpauto_password']
 * $config['api_jpauto_currency']-код валюты обязательно должен быть в системе
 * $config['api_jpauto_day'] - + к сроку поставки
 */
class Jpauto{
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

        $client = new SoapClient("http://jpauto.od.ua/wsdl/server.php?wsdl", array('encoding'=>'cp1251','connection_timeout' => 3));
        $Login = $this->CI->config->item('api_jpauto_login');
        $Passwd = $this->CI->config->item('api_jpauto_password');
        $MakeId = '';
        $UserParam = array('login'=>$Login,'passwd'=>$Passwd);
        $results = $client->getPartsPrice($sku,$MakeId,$UserParam);
        if( $results){
            foreach ( $results as $result){
                $available = str_replace('*',10,$result['PartNameEng']);
                $term = preg_replace("/[^0-9]/","",$result['Postavwik']);

                $excerpt = '';
                if((int)$result['WeightGr'] > 0){
                    $excerpt .= 'Вес:'.(int)$result['WeightGr'] / 1000;
                }

                $excerpt .= ' Процент отгрузки:'.$result['PercentSupped'];
                if($available < 3 || $term > 198){
                    continue;
                }

                $product = [
                    'name' => $result['name'],
                    'sku' => $this->CI->product_model->clear_sku($result['DetailNum']),
                    'brand' =>  $this->CI->product_model->clear_brand($result['MakeName']),
                    'delivery_price' => (float)$result['Price'],
                    'quantity' => $this->CI->config->item('api_jpauto_default_quan') ? $this->CI->config->item('api_jpauto_default_quan') : (int)$available,
                    'supplier_id' => (int)$supplier_id,
                    'term' => $term * 24,
                    'excerpt' => $excerpt
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
