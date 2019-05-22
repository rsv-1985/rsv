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
        $this->load->model('mproduct');
        $this->load->model('product_model');
        $this->load->model('brand_group_model');
        $this->load->language('search');
        $this->load->helper('text');
    }

    public function pre_search()
    {
        $search = strip_tags($this->input->get('search', true));

        $brands = $this->mproduct->getBrands($search);

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

           if(count($brands) == 1 && !$data['group_brands']){
               redirect('/search?search='.$search.'&ID_art='.@$brands[0]['ID_art'].'&brand='.urlencode($brands[0]['brand']));
           }

           if(!$brands && count($data['group_brands']) == 1){
               foreach ($data['group_brands'] as $id => $item){
                   redirect('/search?search='.$search.'&id_group='.$id);
               }
           }

        }else{
            redirect('/search?search='.$search);
        }


        $this->setH1(sprintf(lang('text_search_pre_search_h1'), $search));
        $this->setTitle(sprintf(lang('text_search_pre_search_title'), $search));
        $this->setDescription(sprintf(lang('text_search_pre_search_description'), $search));
        $this->setKeywords(sprintf(lang('text_search_pre_search_keywords'), $search));

        $this->setOg('title',$this->title);
        $this->setOg('description',$this->description);
        $this->setOg('url',current_url());

        $this->load->view('header');
        $this->load->view('search/pre_search', $data);
        $this->load->view('footer');
    }


    public function index()
    {
        $this->load->model('mproduct');

        $search = strip_tags($this->input->get('search', true));

        $brand = $this->input->get('brand', true);

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



        $products_items = [];
        $cross_items = [];

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

        $system_cross = $this->product_model->get_crosses($brand, $search);

        if ($system_cross) {
            foreach ($system_cross as $st){
                $product_search[] = $st;
            }
        }

        //Если указано id_group группа брендов

        if($group_brand_id = (int)$this->input->get('id_group')){

            $group_brands = $this->brand_group_model->getBrands($group_brand_id);

            if($group_brands){
                $this->load->library('td');

                $brandGroup = [];
                foreach ($group_brands as $group_brand){
                    $brandGroup[] = urlencode($group_brand['brand']);
                    $check_brands[] = $group_brand['brand'];
                    $product_search[] = ['sku' => $search, 'brand' =>  $group_brand['brand']];
                }

                $system_cross = $this->product_model->get_crosses($brandGroup, $search);

                if ($system_cross) {
                    foreach ($system_cross as $st){
                        $product_search[] = $st;
                    }
                }
            }
        }

        if(@$group_brands){
            foreach ($group_brands as $group_brand){
                $cross_suppliers = $this->product_model->api_supplier($this->product_model->clear_sku($search), $group_brand['brand'], $product_search);
                if ($cross_suppliers) {
                    foreach ($cross_suppliers as $cross_supplier) {
                        if($cross_supplier){
                            foreach ($cross_supplier as $cs){
                                $product_search[] = $cs;
                            }
                        }
                    }
                }
            }
        }else{
            $cross_suppliers = $this->product_model->api_supplier($this->product_model->clear_sku($search), $brand, $product_search);
            if ($cross_suppliers && $brand) {
                foreach ($cross_suppliers as $cross_supplier) {
                    if($cross_supplier){
                        foreach ($cross_supplier as $cs){
                            $product_search[] = $cs;
                        }
                    }
                }
            }
        }




        $product_search = array_unique($product_search,SORT_REGULAR);

        if ($product_search) {
            $products = $this->product_model->get_search($product_search);

            if ($products) {
                //Массово получаем инфу с текдока
                foreach ($products as $product){
                    $key = md5($product['sku'].$product['brand']);
                    $td[$key] = ['sku' => $product['sku'], 'brand' => $product['brand']];
                }

                $tecdoc_info_array = (array)$this->tecdoc->getArticleArray(array_slice($td, 0 , 100));

                foreach ($products as $product) {
                    if ($product['prices']) {
                        if($product['sku'] != clear_sku($search) || !in_array($product['brand'],$check_brands)){
                            $is_cross = 1;
                        }else{
                            $is_cross = 0;
                        }

                        $product['is_cross'] =  $is_cross;

                        $key = md5($product['sku'].$product['brand']);


                        if(isset($tecdoc_info_array[$key])){
                            $tecdoc_info['article'] = (array)$tecdoc_info_array[$key];
                        }else{
                            $tecdoc_info['article'] = [];
                        }

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

                        if($product['is_cross']){
                            $cross_items[] = $product;
                        }else{
                            $products_items[] = $product;
                        }

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


                        $products_items[] = $product;
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

        if($products_items){
            $like_term = [];
            $other = [];
            foreach ($products_items as $product){
                if($product['prices'] && $product['prices'][0]['term'] < 24){
                    $like_term[] = $product;
                }else{
                    $other[] = $product;
                }
            }

            $data['products'] = array_merge($like_term,$other);
        }else{
            $data['products'] = false;
        }

        if($cross_items){
            $like_term = [];
            $other = [];
            foreach ($cross_items as $product){
                if($product['prices'] && $product['prices'][0]['term'] < 24){
                    $like_term[] = $product;
                }else{
                    $other[] = $product;
                }
            }

            $data['cross'] = array_merge($like_term,$other);
        }



        $this->load->view('header');
        $this->load->view('search/search', $data);
        $this->load->view('footer');
    }
}
