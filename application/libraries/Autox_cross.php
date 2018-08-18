<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Autox_cross{
    private $key;
    private $host = 'https://api.autox.pro/';

    public function __construct($params)
    {
        $this->key = $params['api_key'];
    }


    public function getCrosses($sku, $brand){
        $url = '/v1/cross?article='.$sku.'&brand='.urlencode($brand).'&wrong=0';
        return $this->query($url);
    }

    public function getBrands($sku){
        $url = '/v1/article?article=' . $sku;
        return $this->query($url);
    }

    public function getUser(){
        $url = '/v1/user?';
        return $this->query($url);
    }

    private function query($url){
        if($this->key){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->host.$url.'&key='.$this->key,
                CURLOPT_RETURNTRANSFER => true,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            if($response && $res = json_decode($response)){
                return $res;
            }
        }

        return false;
    }
}

