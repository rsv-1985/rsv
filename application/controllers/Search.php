<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends Front_controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('brand_group_model');
        $this->load->language('search');
        $this->load->helper('text');
    }

    public function pre_search()
    {
        $search = strip_tags($this->input->get('search', true));

        $brands = $this->product_model->get_brands($search);
        if($brands){
            $data['group_brands'] = [];
            $data['brands'] = [];
            //Проверяем есть ли бренды в группе брендов

                foreach ($brands as $i => $brand){
                    $brand_group = $this->brand_group_model->getBrandGroupByBrand($brand['brand']);
                    if($brand_group){
                        unset($brands[$i]);
                        $data['group_brands'][$brand_group['id']] = [
                            'id_group' => $brand_group['id'],
                            'name' => $brand['name'],
                            'brand' => $brand_group['group_name'],
                            'sku' => $brand['sku'],
                            'image' => $brand['image']
                        ];
                    }
                }
            $data['brands'] = $brands;

        }else{
            redirect('/search?search='.$search);
        }


        $this->setH1(sprintf(lang('text_search_pre_search_h1'), $search));
        $this->setTitle(sprintf(lang('text_search_pre_search_title'), $search));
        $this->setDescription(sprintf(lang('text_search_pre_search_description'), $search));
        $this->setKeywords(sprintf(lang('text_search_pre_search_keywords'), $search));

        $this->load->view('header');
        $this->load->view('search/pre_search', $data);
        $this->load->view('footer');
    }


    public function index()
    {
        $search = strip_tags($this->input->get('search', true));

        $brand = $this->input->get('brand', true);

        $ID_art = (int)$this->input->get('ID_art');

        $this->load->library('user_agent');
        //Если это не робот и не админ, пишем историю поиска в базу данных
        if (!$this->agent->is_robot() && !$this->is_admin) {
            $this->load->model('search_history_model');
            $search_history = [
                'customer_id' => (int)$this->is_login,
                'sku' => (string)$search,
                'brand' => (string)$brand
            ];
            $this->search_history_model->insert($search_history);
        }

        //Если не указан ID_art но указан код и бренд получаем id_art с текдока.
        if (!$ID_art && $brand && $search) {
            $tecdoc_id_art = $this->tecdoc->getIDart($this->product_model->clear_sku($search), $brand);
            if ($tecdoc_id_art) {
                $ID_art = $tecdoc_id_art[0]->ID_art;
            }
        }



        $data['products'] = [];
        $data['filter_brands'] = [];

        $data['min_price'] = false;
        $data['min_price_cross'] = false;
        $data['min_term'] = false;

        //Массив для поиска
        $product_search = [];

        //Массив брендов для сверки аналог это или точное совпадение
        $check_brands = [];
        if($brand){
            $check_brands[] = $brand;
            $product_search[] = ['sku' => $search, 'brand' =>  $brand];
        }

        $system_cross = $this->product_model->get_crosses($ID_art, $brand, $search);

        if ($system_cross) {
            foreach ($system_cross as $st){
                $product_search[] = $st;
            }
        }

        //Если указано id_group группа брендов
        if($group_brand_id = (int)$this->input->get('id_group')){
            $group_brands = $this->brand_group_model->getBrands($group_brand_id);
            if($group_brands){
                foreach ($group_brands as $group_brand){
                    $tecdoc_id_art = $this->tecdoc->getIDart($this->product_model->clear_sku($search), $group_brand['brand']);
                    if ($tecdoc_id_art) {
                        $ID_art = $tecdoc_id_art[0]->ID_art;
                    }

                    $system_cross = $this->product_model->get_crosses($ID_art, $group_brand['brand'], $search);

                    if ($system_cross) {
                        foreach ($system_cross as $st){
                            $product_search[] = $st;
                        }
                    }
                    $check_brands[] = $group_brand['brand'];
                    $product_search[] = ['sku' => $search, 'brand' =>  $group_brand['brand']];
                }
            }
        }

        $cross_suppliers = $this->product_model->api_supplier($this->product_model->clear_sku($search), $brand, $product_search);

        if ($cross_suppliers) {
            foreach ($cross_suppliers as $cross_supplier) {
                if($cross_supplier){
                    $post = [
                        'sku' => $search,
                        'brand' => $brand,
                        'cross' => $cross_supplier
                    ];
                    $ch = curl_init('https://api.autox.pro/import/insert');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
                    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
                    curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 10);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    foreach ($cross_supplier as $cs){
                        $product_search[] = $cs;
                    }
                }
            }
        }

        $product_search = array_unique($product_search,SORT_REGULAR);

        if ($product_search) {
            $products = $this->product_model->get_search($product_search);

            if ($products) {
                foreach ($products as $product) {
                    if ($product['prices']) {
                        if($product['sku'] != $this->product_model->clear_sku($search) || !in_array($product['brand'],$check_brands)){
                            $is_cross = 1;
                        }else{
                            $is_cross = 0;
                        }
                        $product['is_cross'] =  $is_cross;
                        $tecdoc_info = $this->product_model->tecdoc_info($product['sku'], $product['brand']);

                        //Если активна опция использовать наименования с текдок
                        if (@$this->options['use_tecdoc_name'] && @$tecdoc_info['article']['Name']) {
                            $product['name'] = @$tecdoc_info['article']['Name'];
                        }


                        if(!$product['image']){
                            $product['image'] =  @$tecdoc_info['article']['Image'];
                        }else{
                            $product['image'] = '/uploads/product/'.$product['image'];
                        }

                        $product['info'] = @$tecdoc_info['article']['Info'];

                        $filter_brands[] = $product['brand'];
                        foreach ($product['prices'] as &$price) {
                            $p = $price['saleprice'] > 0 ? $price['saleprice'] : $price['price'];

                            if($product['is_cross'] == 1){
                                if(!$data['min_price_cross'] || $p < $data['min_price_cross']['price']){
                                    $data['min_price_cross'] = [
                                        'id' => $product['id'],
                                        'supplier_id' => $price['supplier_id'],
                                        'slug' => $product['slug'],
                                        'sku' => $product['sku'],
                                        'brand' => $product['brand'],
                                        'name' => $product['name'],
                                        'image' => $product['image'],
                                        'price' => $p,
                                        'term' => $price['term'],
                                        'key' => $product['id'] . $price['supplier_id'] . $price['term']
                                    ];
                                }
                            }

                            if($product['is_cross'] == 0){
                                if(!$data['min_price'] || $p < $data['min_price']['price']){
                                    $data['min_price'] = [
                                        'id' => $product['id'],
                                        'supplier_id' => $price['supplier_id'],
                                        'slug' => $product['slug'],
                                        'sku' => $product['sku'],
                                        'brand' => $product['brand'],
                                        'name' => $product['name'],
                                        'image' => $product['image'],
                                        'price' => $p,
                                        'term' => $price['term'],
                                        'key' => $product['id'] . $price['supplier_id'] . $price['term']
                                    ];
                                }
                            }


                            if(!$data['min_term'] || $price['term'] < $data['min_term']['term']){
                                $data['min_term'] = [
                                    'id' => $product['id'],
                                    'supplier_id' => $price['supplier_id'],
                                    'slug' => $product['slug'],
                                    'sku' => $product['sku'],
                                    'brand' => $product['brand'],
                                    'name' => $product['name'],
                                    'image' => $product['image'],
                                    'price' => $p,
                                    'term' => $price['term'],
                                    'key' => $product['id'] . $price['supplier_id'] . $price['term']
                                ];
                            }

                            $price['key'] = $product['id'] . $price['supplier_id'] . $price['term'];
                        }

                        $data['products'][] = $product;
                    }
                }
            }

        } else {

            $products = $this->product_model->get_search_text($search);
            if ($products) {
                foreach ($products as $product) {
                    if ($product['prices']) {
                        $product['is_cross'] = 2;
                        $tecdoc_info = $this->product_model->tecdoc_info($product['sku'], $product['brand']);

                        //Если активна опция использовать наименования с текдок
                        if ($this->options['use_tecdoc_name'] && @$tecdoc_info['article']['Name']) {
                            $product['name'] = @$tecdoc_info['article']['Name'];
                        }


                        if(!$product['image']){
                            $product['image'] =  @$tecdoc_info['article']['Image'];
                        }else{
                            $product['image'] = '/uploads/product/'.$product['image'];
                        }

                        $product['info'] = @$tecdoc_info['article']['Info'];

                        $filter_brands[] = $product['brand'];
                        foreach ($product['prices'] as &$price) {
                            $price['key'] = $product['id'] . $price['supplier_id'] . $price['term'];
                        }

                        $data['products'][] = $product;
                    }
                }
            }
        }

        $this->setH1(@sprintf(lang('text_search_search_h1'), $search, $brand));
        $this->setTitle(@sprintf(lang('text_search_search_title'), $search, $brand));
        $this->setDescription(@sprintf(lang('text_search_search_description'), $search, $brand));
        $this->setKeywords(@sprintf(lang('text_search_search_keywords'), $search, $brand));

        if (isset($filter_brands)) {
            $data['filter_brands'] = array_unique($filter_brands);
            sort($data['filter_brands'], SORT_STRING);
        }

        usort($data['products'], function ($a, $b) {
            if ($a['is_cross'] == $b['is_cross']) {
                return 0;
            }
            return ($a['is_cross'] < $b['is_cross']) ? -1 : 1;
        });

        $this->load->view('header');
        $this->load->view('search/search', $data);
        $this->load->view('footer');
    }
}
