<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Доступы в конфиг файл
 * AutoEuro-Online
 */
class Autoeuro{
    public $CI;
    public $client_name;
    public $client_pwd;
    public $server;
    public $currency_id;
    public $term;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('product_model');
        $this->client_name = $this->CI->config->item('api_autoeuro_client_name');
        $this->client_pwd = $this->CI->config->item('api_autoeuro_client_pwd');
        $this->server = $this->CI->config->item('api_autoeuro_server');
        $this->currency_id = $this->CI->config->item('api_autoeuro_currency_id');
    }

    public function get_search($supplier_id, $sku, $brand, $search_data){
        $cross_supplier = [];
        //Удаляем все ценовые предложения перед поиском
        $this->CI->product_model->product_delete(['supplier_id' => (int)$supplier_id, 'updated_at <' => date('Y-m-d H:i:s', strtotime('- 1 day'))]);
        if($brand){
            $results = $this->getData('Get_Element_Details',[$brand,$sku,1]);
            if($results){
                foreach ($results as $result){
                    if(!isset($result['name'])){
                        continue;
                    }

                    switch ($result['order_time']){
                        case '':
                            $term = $this->CI->config->item('api_autoeuro_term_in_stock');
                            break;
                        case '0-0':
                            $term = $this->CI->config->item('api_autoeuro_term_0_0');
                            break;
                        default:
                            $term = (int)$result['order_time'] * 24 + $this->CI->config->item('api_autoeuro_term_other');
                    }


                    $product = [
                        'name' => mb_convert_encoding($result['name'],'UTF-8','windows-1251'),
                        'sku' =>  $this->CI->product_model->clear_sku(mb_convert_encoding($result['code'],'UTF-8','windows-1251')),
                        'brand' =>  $this->CI->product_model->clear_brand(mb_convert_encoding($result['maker'],'UTF-8','windows-1251')),
                        'delivery_price' => $result['price'],
                        'quantity' => (int)$result['amount'],
                        'supplier_id' => (int)$supplier_id,
                        'term' => $term
                    ];

                    if($result['is_kross']){
                        $cross_supplier[]=[
                            'sku' =>  $product['sku'],
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
                        'currency_id' => $this->CI->db->escape($this->currency_id),
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

        return $cross_supplier;
    }

    private function getData($proc,$parm=false) {
        if(!$parm) $parm = array();
        $command = array('proc_id'=>$proc,'parm'=>$parm);
        $auth = array('client_name'=>$this->client_name,'client_pwd'=>$this->client_pwd);
        $data = array('command'=>$command,'auth'=>$auth);
        $data = $this->sendPost($this->server,$data);
        return $data;
    }

    private function sendPost($url,$data) {
        $data = array('postdata'=>serialize($data));
        $data = array_map('base64_encode',$data);
        $data = http_build_query($data);
        $post = $this->genPost($url,$data);
        $url = parse_url($url);
        $fp = @fsockopen($url['host'], 80, $errno, $errstr, 30);
        if (!$fp) return false;
        $responce = '';
        fwrite($fp,$post);
        while ( !feof($fp) )
            $responce .= fgets($fp);
        fclose($fp);
        $responce = $this->NormalizePostResponce($responce);
        return $responce;
    }

    private function genPost($url,$data) {
        $url = parse_url($url);
        $post = 'POST '.@$url['path']." HTTP/1.0\r\n";
        $post .= 'Host: '.$url['host']."\r\n";
        $post .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $post .= "Accept-Charset: windows-1251\r\n";
        $post .= 'Content-Length: '.strlen($data)."\r\n\r\n";
        $post .= $data;
        return $post;
    }

    private function NormalizePostResponce($responce) {
        $responce = explode("\r\n\r\n",$responce);	// отделим header(s)
        $responce = array_pop($responce);	// извлечем данные
        $responce = array_map('base64_decode',array($responce));
        $responce = unserialize($responce[0]);
        return $responce;
    }

}