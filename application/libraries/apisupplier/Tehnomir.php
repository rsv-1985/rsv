﻿<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Tehnomir
{
    public $CI;

    private $login = 'coolerandrew45@gmail.com';
    private $password = 'sad0301';
    private $currency = 'USD';


    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('product_model');
    }

    public function get_search($supplier_id, $sku, $brand, $search_data)
    {
        $currency_info = $this->CI->currency_model->get(6);

        $cross_supplier = [];
        //Удаляем все ценовые предложения перед поиском
        $this->CI->product_model->product_delete(['supplier_id' => (int)$supplier_id, 'updated_at <' => date('Y-m-d H:i:s', strtotime('- 1 day'))]);

        $login = $this->login;
        $password = $this->password;
        $currency = $this->currency;
        $plus_day = 1;

        $url = 'https://www.tehnomir.com.ua/ws/xml.php?act=GetPriceWithCrosses&usr_login=' . $login . '&Currency=' . $currency . '&usr_passwd=' . $password . '&PartNumber=' . $sku;

        $results = $this->get_products($url);

        if (isset($results['QueryStatus']) && $results['QueryStatus']['QueryStatusCode'] == 1) {
            if ($brand) {
                foreach ($results['Producers']['Producer'] as $producer) {
                    if ($producer['Brand'] == $brand) {
                        $url = 'https://www.tehnomir.com.ua/ws/xml.php?act=GetPriceWithCrosses&usr_login=' . $login . '&currency=' . $currency . '&usr_passwd=' . $password . '&PartNumber=' . $sku . '&BrandId=' . $producer['BrandId'];
                        $results = $this->get_products($url);
                        break;
                    }
                }
            }
        }


        if (isset($results['QueryStatus']) && $results['QueryStatus']['QueryStatusCode'] == 0 && count($results['Prices']) > 0) {

            foreach ($results['Prices']['Price'] as $result) {

                if (!isset($result['Price']) || $result['DeliveryPercent'] < 45) {
                    continue;
                }



                $d = false;
                switch ($result['DeliveryType']) {
                    case 'AIR':
                        $d = (float)$result['Weight'] * 7;
                        $excerpt = '<img src="/uploads/air.png"/>';
                        break;
                    case 'CONTAINER':
                        $d = (float)$result['Weight'] * 4;
                        $excerpt = '<img src="/uploads/container.png"/>';
                        break;
                    case 'LOCAL':
                        $excerpt = '<img src="/uploads/local.png"/>';
                        break;
                    default:
                        $excerpt = '';
                        break;
                }

                if ($result['ReturnFlag'] == 'Y') {
                    $excerpt .= '<img src="/uploads/return_y.png" title="Полная предоплата. Возврат в течении 5-ти дней"/>';
                } else {
                    $excerpt .= '<img src="/uploads/return_n.png" title="Полная предоплата. Возврат не возможен"/>';
                }


                $product = [
                    'name' => (string)$result['PartDescriptionRus'],
                    'sku' => $this->CI->product_model->clear_sku($result['PartNumberShort']),
                    'brand' => $this->CI->product_model->clear_brand($result['Brand']),
                    'delivery_price' => (float)$result['Price'],
                    'quantity' => (int)$result['Quantity'] <= 0 ? 1 : $result['Quantity'],
                    'supplier_id' => (int)$supplier_id,
                    'term' => ((int)$result['DeliveryDays'] + (int)$plus_day) * 24
                ];



//привет, помоги пож. в апи ТМ ограничить срок поставки не более 25 дней
                if ($product['term'] > 600) {
                    continue;
                }

                if ($product['sku'] != $sku || $product['brand'] != $brand) {
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

                if (isset($price_data[$product_id . $product['term']]) && $price_data[$product_id . $product['term']]['delivery_price'] <= $product['delivery_price']) {
                    continue;
                } else {
                    $price_data[$product_id . $product['term']] = [
                        'product_id' => $this->CI->db->escape($product_id),
                        'excerpt' => $this->CI->db->escape($excerpt),
                        'currency_id' => $this->CI->db->escape(6),
                        'delivery_price' => $this->CI->db->escape($product['delivery_price'] + round($d)),
                        'saleprice' => $this->CI->db->escape(0),
                        'quantity' => $this->CI->db->escape($product['quantity']),
                        'supplier_id' => $this->CI->db->escape($supplier_id),
                        'term' => $this->CI->db->escape($product['term']),
                        'created_at' => $this->CI->db->escape(date("Y-m-d H:i:s")),
                        'updated_at' => $this->CI->db->escape(date("Y-m-d H:i:s")),
                    ];
                }

            }

            if (@$price_data) {
                $this->CI->product_model->price_insert($price_data);
            }
        }

        return $cross_supplier;
    }

    public function get_brands($sku){
        $sku = clear_sku($sku);
        $url = 'https://www.tehnomir.com.ua/ws/xml.php?act=GetPriceWithCrosses&usr_login=' . $this->login . '&Currency=' . $this->currency . '&usr_passwd=' . $this->password . '&PartNumber=' . $sku;

        $results = $this->get_products($url);

        if($results && isset($results['Producers']['Producer'])){
            foreach ($results['Producers']['Producer'] as $brand){

                $product = [
                    'name' => $brand['PartDescriptionRus'],
                    'sku' => $sku,
                    'brand' => clear_brand($brand['Brand']),
                ];


                $product_data = [
                    'sku' => $sku,
                    'brand' => $product['brand'],
                    'name' => $product['name'],
                    'slug' => $this->CI->product_model->getSlug($product),
                ];

                $product_id = $this->CI->product_model->product_insert($product_data, false, false);

            }
        }
    }

    public function get_products($url)
    {
        if($_SERVER['REMOTE_ADDR'] == '178.213.1.213'){
            echo($url).'<br/>';
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);


        if ($res != '') {
            $xml = simplexml_load_string($res);
            $json = json_encode($xml);
            return json_decode($json, TRUE);
        }
    }
}
