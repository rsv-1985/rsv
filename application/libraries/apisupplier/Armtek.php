<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Доступы в конфиг файл
 * $config['api_tehnomir_login']
 * $config['api_tehnomir_password']
 * $config['api_tehnomir_currency']-код валюты обязательно должен быть в системе
 * $config['api_tehnomir_plus_day'] - + к сроку поставки
 */
class Armtek{
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

        $login = $this->CI->config->item('api_armtek_login');;
        $password = $this->CI->config->item('api_armtek_password');
        $currency_id = $this->CI->config->item('api_armtek_currency_id');
        $plus_day = $this->CI->config->item('api_armtek_plus_day');

        //Получаем сбытовую организацию
        $VKORG = false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'http://ws.armtek.ru/api/ws_user/getUserVkorgList?format=json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERPWD, $login.':'.$password);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $result = json_decode(curl_exec($ch));
        if($result->STATUS == 200){
            $VKORG = $result->RESP[0]->VKORG;
        }

        //Получаем KUNNR_RG
        $KUNNR_RG = false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'http://ws.armtek.ru/api/ws_user/getUserInfo?format=json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERPWD, $login.':'.$password);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "VKORG=".$VKORG."&STRUCTURE=1");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        $result = json_decode(curl_exec($ch));

        if($result->STATUS == 200){
            $KUNNR_RG = $result->RESP->STRUCTURE->RG_TAB[0]->KUNNR;
        }


        if($VKORG && $KUNNR_RG){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,'http://ws.armtek.ru/api/ws_search/search?format=json');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_USERPWD, $login.':'.$password);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "VKORG=".$VKORG."&KUNNR_RG=".$KUNNR_RG."&PIN=".$sku."&BRAND=".$brand."&QUERY_TYPE=2");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            $results = json_decode(curl_exec($ch));

            if($results->STATUS == 200){
                foreach ($results->RESP as $result){


                    $delivery_data = (date('Y-m-d',strtotime($result->DLVDT)));
                    $datetime1 = new DateTime(date('Y-m-d'));
                    $datetime2 = new DateTime($delivery_data);
                    $interval = $datetime1->diff($datetime2);
                    $period = $interval->format('%d');

                    $term = ($period + $plus_day) * 24;

                    $product = [
                        'name' => $result->NAME,
                        'sku' => $this->CI->product_model->clear_sku($result->PIN),
                        'brand' =>  $this->CI->product_model->clear_brand($result->BRAND),
                        'delivery_price' => (float)$result->PRICE,
                        'quantity' => $this->CI->product_model->clear_quan($result->RVALUE),
                        'supplier_id' => (int)$supplier_id,
                        'term' => (int)$term
                    ];

                    if($result->ANALOG){
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
                        'excerpt' => $this->CI->db->escape(''),
                        'currency_id' => $this->CI->db->escape($currency_id),
                        'delivery_price' => $this->CI->db->escape($product['delivery_price']),
                        'saleprice' => $this->CI->db->escape(0),
                        'quantity' => $this->CI->db->escape($product['quantity']),
                        'supplier_id' => $this->CI->db->escape($supplier_id),
                        'term' => $this->CI->db->escape($product['term']),
                        'created_at' => $this->CI->db->escape(date("Y-m-d H:i:s")),
                        'updated_at' => $this->CI->db->escape(date("Y-m-d H:i:s")),
                        'status' => true,
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
