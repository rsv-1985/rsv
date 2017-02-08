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

        $system_currency_id = 0;
        foreach ($this->CI->product_model->currency_rates as $system_currency){
            if($system_currency['code'] == $currency){
                $system_currency_id = $system_currency['id'];
            }
        }

        $client = @new SoapClient($soapUrl);
        try {
            $results = $client->GetPrice($sku, $brand, $login, $password, $currency);

           foreach ($results as $result){

              $product = [
                  'name' => $result['Name'],
                  'sku' => $result['Number'],
                  'brand' => $result['Brand'],
                  'delivery_price' => $result['Price'],
                  'quantity' => $result['Quantity'],
                  'supplier_id' => (int)$supplier_id,
                  'term' => $result['DeliveryTime'] * 24 + (int)$plus_day
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

            if(@$price_data){
                $this->CI->product_model->price_insert($price_data);
            }

            $this->CI->product_model->set_price($supplier_id);

        } catch ( SoapFault $exception ) {
            
        }
    }
}
