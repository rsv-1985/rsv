<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends Front_controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->language('search');
    }


    public function index(){

        $ID_art = (int)$this->input->get('ID_art');
        $brand = $this->input->get('brand', true);
        $search = strip_tags($this->input->get('search', true));

        $data['brands'] = $this->product_model->get_brands($search);
        //Если есть бренды и пользователь не указал при первом поиске бренд, выбираем первы из списка.
        if($data['brands'] && !$brand){
            redirect('/search?search='.$search.'&brand='.$data['brands'][0]['brand'].'&ID_art='.$data['brands'][0]['ID_art']);
        }

        $this->load->library('user_agent');
        //Если это не робот, пишем историю поиска в базу данных
        if(!$this->agent->is_robot()){
            $this->load->model('search_history_model');
            $search_history = [
                'customer_id' => (int)$this->is_login,
                'sku' => (string)$search,
                'brand' => (string)$brand
            ];
            $this->search_history_model->insert($search_history);
        }

        $crosses_search = [];
        if($ID_art){
            $crosses_search = $this->product_model->get_crosses($ID_art, $brand, $search);
        }

        $cross_suppliers = $this->product_model->api_supplier($search, $brand, $crosses_search);

        if($cross_suppliers){
            foreach ($cross_suppliers as $cross_supplier){
                $crosses_search = array_merge($crosses_search,$cross_supplier);
            }
            $crosses_search = array_unique($crosses_search, SORT_REGULAR);
        }

        $data['products'] = [];
        $data['filter_brands'] = [];

        if($brand && $search){
            $product = $this->product_model->get_search_products($search, $brand);
            if($product && $product['prices']){
                $filter_brands[] = $product['brand'];
                foreach ($product['prices'] as $price){
                    $data['products'][] = [
                        'id' => $product['id'],
                        'sku' => $product['sku'],
                        'name' => $product['name'],
                        'brand' => $product['brand'],
                        'price' => $price['saleprice'] > 0 ? $price['saleprice'] : $price['price'],
                        'delivery_price' => $price['delivery_price'],
                        'currency_id' => $price['currency_id'],
                        'term' => $price['term'],
                        'quantity' => $price['quantity'],
                        'supplier_id' => $price['supplier_id'],
                        'cross' => 0,
                        'excerpt' => $price['excerpt'],
                        'updated_at' => $price['updated_at'],
                        'slug' => $product['slug']
                    ];
                }
            }

            if($crosses_search){
                $crosses = $this->product_model->get_search_crosses($crosses_search);
                if($crosses){
                    foreach ($crosses as $product){
                        if($product['prices']){
                            $filter_brands[] = $product['brand'];
                            foreach ($product['prices'] as $price){
                                $data['products'][] = [
                                    'id' => $product['id'],
                                    'sku' => $product['sku'],
                                    'name' => $product['name'],
                                    'brand' => $product['brand'],
                                    'price' => $price['saleprice'] > 0 ? $price['saleprice'] : $price['price'],
                                    'delivery_price' => $price['delivery_price'],
                                    'currency_id' => $price['currency_id'],
                                    'term' => $price['term'],
                                    'quantity' => $price['quantity'],
                                    'supplier_id' => $price['supplier_id'],
                                    'cross' => 1,
                                    'excerpt' => $price['excerpt'],
                                    'updated_at' => $price['updated_at'],
                                    'slug' => $product['slug']
                                ];
                            }
                        }
                    }
                }
            }

        }elseif(!$data['products']){
            $products = $this->product_model->get_search_text($search);
            if($products){
                foreach ($products as &$product){
                    $filter_brands[] = $product['brand'];
                    $product['cross'] = 2;
                }
                $data['products'] = $products;
            }
        }

        $data['h1'] = $search.' '.$brand;

        if(isset($filter_brands)){
            $data['filter_brands'] = array_unique($filter_brands);
            sort($data['filter_brands'],SORT_STRING);
        }

        $this->load->view('header');
        $this->load->view('search/search', $data);
        $this->load->view('footer');
    }
}
