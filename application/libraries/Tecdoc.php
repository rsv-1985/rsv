<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Tecdoc
{

    private $key;
    private $url = 'http://www.autoxcatalog.com/api?query=';
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->key = $this->CI->config->item('api_key');
        $this->url = $this->CI->config->item('api_url');
    }

    public function getArticleArray($array){
        foreach ($array as &$item){
            $item['brand'] = urlencode(mb_strtoupper(str_replace(['+',' ','-'],['','',''],$item['brand']), 'UTF-8'));
        }
        $query = [
            'apikey' => $this->key,
            'method' => 'getArticleArray',
            'params' => ['items' => $array]
        ];

        return $this->res($query);
    }

    public function getManufacturerYear($year)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getManufacturer',
            'params' => ['year' => $year]
        ];

        return $this->res($query, true);
    }

    public function getModelYear($ID_mfa, $year)
    {

        $query = [
            'apikey' => $this->key,
            'method' => 'getModel',
            'params' => [
                'ID_mfa' => $ID_mfa,
                'year' => $year
            ]
        ];
        return $this->res($query, true);
    }

    public function getTypeYear($ID_mod, $year)
    {

        $query = [
            'apikey' => $this->key,
            'method' => 'getType',
            'params' => [
                'ID_mod' => $ID_mod,
                'year' => $year
            ]
        ];

        return $this->res($query, true);
    }


    public function getManufacturer($ID_mfa = false)
    {
        if ($ID_mfa) {
            $query = [
                'apikey' => $this->key,
                'method' => 'getManufacturer',
                'params' => ['ID_mfa' => $ID_mfa]
            ];
        } else {
            $query = [
                'apikey' => $this->key,
                'method' => 'getManufacturer'
            ];
        }
        return $this->res($query, true);
    }

    public function getModel($ID_mfa, $ID_mod = false)
    {
        if ($ID_mod) {
            $query = [
                'apikey' => $this->key,
                'method' => 'getModel',
                'params' => [
                    'ID_mfa' => $ID_mfa,
                    'ID_mod' => $ID_mod
                ]
            ];
        } else {
            $query = [
                'apikey' => $this->key,
                'method' => 'getModel',
                'params' => [
                    'ID_mfa' => $ID_mfa
                ]
            ];
        }
        return $this->res($query, true);
    }

    public function getType($ID_mod, $ID_typ = false)
    {
        if ($ID_typ) {
            $query = [
                'apikey' => $this->key,
                'method' => 'getType',
                'params' => [
                    'ID_mod' => $ID_mod,
                    'ID_typ' => $ID_typ
                ]
            ];
        } else {
            $query = [
                'apikey' => $this->key,
                'method' => 'getType',
                'params' => [
                    'ID_mod' => $ID_mod,
                ]
            ];
        }
        return $this->res($query, true);
    }

    public function getTree($ID_typ, $ID_tree = 10001)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getTree',
            'params' => [
                'ID_typ' => $ID_typ,
                'ID_tree' => $ID_tree
            ]
        ];

        return $this->res($query, true);
    }

    public function getParts($ID_typ, $ID_tree)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getParts',
            'params' => [
                'ID_typ' => $ID_typ,
                'ID_tree' => $ID_tree
            ]
        ];

        return $this->res($query, true);
    }

    public function getArticle($ID_art)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getArticle',
            'params' => [
                'ID_art' => $ID_art
            ]
        ];

        return $this->res($query);
    }

    public function getCrosses($ID_art)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getCrosses',
            'params' => [
                'ID_art' => $ID_art
            ]
        ];

        return $this->res($query);
    }

    public function getSearch($query)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getSearch',
            'params' => [
                'query' => $query
            ]
        ];

        return $this->res($query);
    }

    public function getIDart($article, $brand)
    {
        $brand = urlencode(mb_strtoupper(str_replace(['+',' ','-'],['','',''],$brand), 'UTF-8'));
        $query = [
            'apikey' => $this->key,
            'method' => 'getSearch',
            'params' => [
                'article' => $article,
                'brand' => $brand
            ]
        ];
        return $this->res($query);
    }

    public function checkProducts($products)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getSearch',
            'params' => $products
        ];

        return $this->res($query);
    }

    public function getTreeAll($ID_typ)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getTreeAll',
            'params' => [
                'ID_typ' => $ID_typ,
            ]
        ];

        return $this->res($query, true);
    }

    public function getTreeNode($ID_tree)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getTreeNode',
            'params' => [
                'ID_tree' => $ID_tree,
            ]
        ];

        return $this->res($query, true);
    }

    public function getPackage($ID_art)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getPackage',
            'params' => [
                'ID_art' => $ID_art
            ]
        ];

        return $this->res($query);
    }

    public function getUses($ID_art)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getUses',
            'params' => [
                'ID_art' => $ID_art
            ]
        ];

        return $this->res($query);
    }

    public function getImages($ID_art)
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getImages',
            'params' => [
                'ID_art' => $ID_art
            ]
        ];

        return $this->res($query);
    }

    public function getYears()
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getYears',
            'params' => []
        ];

        return $this->res($query, true);
    }

    public function getTreeFull()
    {
        $query = [
            'apikey' => $this->key,
            'method' => 'getTreeFull',
            'params' => []
        ];

        return $this->res($query, true);
    }

    public function res($query, $use_cache = false)
    {
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

        $res = curl_exec($curl);
        curl_close($curl);

        $check = json_decode($res);
        if (isset($check->Data)) {
            if ($use_cache) {
                $this->CI->cache->file->save($key, $check->Data, 60*60*24*30);
            }
            return $check->Data;
        } else {
            return false;
        }
    }
}