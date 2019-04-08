<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');


class Td {
    private $key;
    private $url = 'https://td2018q2.autox.pro?json=';
    private $CI;
    private $curl;

    private $image_path = 'https://td2018q2.autox.pro/images/';

    public function __construct()
    {
        $this->CI = &get_instance();
        //$this->key = $this->CI->config->item('api_key');
        //$this->url = $this->CI->config->item('api_url');
    }

    public function getInfo(){

    }

    public function getImages($sku, $brand){
        $query = [
            'apikey' => $this->key,
            'method' => 'getImages',
            'params' => [
                'article' => $sku,
                'brand' => $brand
            ]
        ];

        $images = $this->res($query);
        if($images){
            $return = [];
            foreach ($images as $image){
                $return[] = $this->image_path.$image;
            }
            return $return;
        }
    }

    public function getOeCross($sku, $brand){

        $query = [
            'apikey' => $this->key,
            'method' => 'getOeCross',
            'params' => [
                'article' => $sku,
                'brand' => $brand
            ]
        ];

        return $this->res($query);
    }

    public function getCrosses($sku, $brand){
        $query = [
            'apikey' => $this->key,
            'method' => 'getCrosses',
            'params' => [
                'sku' => $sku,
                'brand' => $brand
            ]
        ];

        return $this->res($query);
    }

    public function getCrossesBrandGroup($sku, $brand_group){
        $query = [
            'apikey' => $this->key,
            'method' => 'getCrossesBrandGroup',
            'params' => [
                'sku' => $sku,
                'brand_group' => $brand_group
            ]
        ];

        return $this->res($query);
    }

    public function res($query, $use_cache = false)
    {
        $this->CI->benchmark->mark('1');
        if ($use_cache) {
            $key = md5(json_encode($query));
            $cache = $this->CI->cache->file->get($key);
            if ($cache) {
                return $cache;
            }
        }

        $jsonurl = $this->url . json_encode($query);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $jsonurl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl,CURLOPT_ENCODING, 1);

        $res = curl_exec($curl);
        curl_close($curl);

        if($res){
            $res = json_decode(gzdecode($res), true);
            if ($use_cache) {
                $this->CI->cache->file->save($key, $res, 60*60*24*30);
            }
            return $res;
        }
    }
}
