<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Доступы в конфиг файл
 * $config['api_partline_usr_login']
 * $config['api_partline_usr_passwd']
 * $config['api_partline_currency']-код валюты обязательно должен быть в системе
 * $config['api_partline_plus_day'] - + к сроку поставки
 */
class Partline{
    public $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('product_model');
    }

    public function get_search($supplier_id, $sku, $brand, $search_data){
        $cross_supplier = [];
        $xmlUrl = 'http://partline.com.ua/export/get_detail.php?act=GetPrice&usr_login='.$this->CI->config->item('api_partline_usr_login').'&usr_passwd='.$this->CI->config->item('api_partline_usr_passwd').'&prn=1&Number='.$sku;
        $xmlStr = file_get_contents($xmlUrl);
        //ѕреобразуем содежимое файла в UTF-8 (парсер с другой кодировкой не будет работать)
        $xmlStr = iconv('WINDOWS-1251', 'UTF-8', $xmlStr);
        $xmlObj = simplexml_load_string($xmlStr);
        $arrXml = $this->objectsIntoArray($xmlObj);
        $tovars_jap_com = array();
        foreach ($arrXml['Detail'] as $detail_info )
        {
            $product = [
                'name' => @(string)$detail_info['Name'],
                'sku' => $this->CI->product_model->clear_sku((string)$detail_info['Number']),
                'brand' =>  $this->CI->product_model->clear_brand((string)$detail_info['Brand']),
                'delivery_price' => (float)$detail_info['Price'],
                'quantity' => (string)$detail_info['Quantity'],
                'supplier_id' => (int)$supplier_id,
                'term' => ($detail_info['Days'] + $this->CI->config->item('api_partline_plus_day')) * 24,
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
                'currency_id' => $this->CI->db->escape($this->CI->config->item('api_partline_currency')),
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

        return $cross_supplier;
    }

    private function objectsIntoArray($arrObjData, $arrSkipIndices = array())
    {
        $arrData = array();
        // if input is object, convert into array
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }
        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value =$this->objectsIntoArray($value, $arrSkipIndices); // recursive call
                }
                if (in_array($index, $arrSkipIndices)) {
                    continue;
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }
}