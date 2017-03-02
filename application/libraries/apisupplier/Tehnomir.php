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
class Tehnomir{
    public $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('product_model');
    }

    public function get_search($supplier_id, $sku, $brand, $search_data){
        //Удаляем все ценовые предложения перед поиском
        $this->CI->product_model->product_delete(['supplier_id' => (int)$supplier_id]);

        $soapUrl = 'http://tehnomir.com.ua/ws/soap.wsdl';
        $login = $this->CI->config->item('api_tehnomir_login');;
        $password = $this->CI->config->item('api_tehnomir_password');
        $currency = $this->CI->config->item('api_tehnomir_currency');
        $plus_day =  $this->CI->config->item('api_tehnomir_plus_day');

        $url = 'https://www.tehnomir.com.ua/ws/xml.php?act=GetPriceWithCrosses&usr_login='.$login.'&currency='.$currency.'&usr_passwd='.$password.'&PartNumber='.$sku;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $res = curl_exec($curl);
        curl_close($curl);

        if($res != ''){
            $xml = simplexml_load_string($res);
            $json = json_encode($xml);
            $results = json_decode($json,TRUE);
            if(isset($results['QueryStatus']) && $results['QueryStatus']['QueryStatusCode'] == 0 && count($results['Prices']) > 0){

                $system_currency_id = 0;
                foreach ($this->CI->currency_model->currencies as $system_currency){
                    if($system_currency['code'] == $currency){
                        $system_currency_id = $system_currency['id'];
                    }
                }


                foreach ($results['Prices']['Price'] as $result) {
                    if ($result['Quantity'] > 0) {
                        $product = [
                            'name' => $result['PartDescriptionRus'],
                            'sku' => $result['PartNumberShort'],
                            'brand' => $result['Brand'],
                            'delivery_price' => $result['Price'],
                            'quantity' => $result['Quantity'],
                            'supplier_id' => (int)$supplier_id,
                            'term' => $result['DeliveryDays'] * 24 + (int)$plus_day
                        ];

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
                            'currency_id' => $this->CI->db->escape($system_currency_id),
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
                }

                if(@$price_data){
                    $this->CI->product_model->price_insert($price_data);
                }

                $this->CI->product_model->set_price($supplier_id);
            }
        }
    }
}
