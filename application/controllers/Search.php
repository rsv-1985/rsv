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
        $this->load->language('search');
    }

    public function pre_search(){
        $search = strip_tags($this->input->get('search', true));
        $data['brands'] = $this->product_model->get_brands($search);

        if(count($data['brands']) == 1){
            redirect('/search?search='.$data['brands'][0]['sku'].'&ID_art='.$data['brands'][0]['ID_art'].'&brand='.$data['brands'][0]['brand']);
        }

        $this->setH1(sprintf(lang('text_search_pre_search_h1'),$search));
        $this->setTitle(sprintf(lang('text_search_pre_search_title'),$search));
        $this->setDescription(sprintf(lang('text_search_pre_search_description'),$search));
        $this->setKeywords(sprintf(lang('text_search_pre_search_keywords'),$search));

        $this->load->view('header');
        $this->load->view('search/pre_search', $data);
        $this->load->view('footer');
    }


    public function index()
    {
        $search = strip_tags($this->input->get('search', true));

        $brand = $this->input->get('brand', true);

        $brands = $this->product_model->get_brands($search);

        if(!$brand && $brands){
            redirect('search/pre_search?search='.$search);
        }


        $ID_art = (int)$this->input->get('ID_art');

        if (!$ID_art && $brand && $search) {
            $tecdoc_id_art = $this->tecdoc->getIDart($this->product_model->clear_sku($search), $brand);
            if ($tecdoc_id_art) {
                $ID_art = $tecdoc_id_art[0]->ID_art;
            }
        }

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

        $crosses_search = array();

        $system_cross = $this->product_model->get_crosses($ID_art, $brand, $search);

        if ($system_cross) {
            $crosses_search = $system_cross;
        }



        $cross_suppliers = $this->product_model->api_supplier($this->product_model->clear_sku($search), $brand, $crosses_search);

        if ($cross_suppliers) {
            foreach ($cross_suppliers as $cross_supplier) {
                $crosses_search = array_merge($crosses_search, $cross_supplier);
            }
        }

        $crosses_search = array_unique($crosses_search, SORT_REGULAR);

        $data['products'] = [];
        $data['filter_brands'] = [];

        if ($brand && $search) {
            $product = $this->product_model->get_search_products($search, $brand);
            if ($product && $product['prices']) {
                $filter_brands[] = $product['brand'];
                foreach ($product['prices'] as $price) {
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
        } else {
            $products = $this->product_model->get_search_text($search);
            if ($products) {
                foreach ($products as $product) {
                    $filter_brands[] = $product['brand'];
                    $data['products'][] = [
                        'id' => $product['product_id'],
                        'sku' => $product['sku'],
                        'name' => $product['name'],
                        'brand' => $product['brand'],
                        'price' => $product['saleprice'] > 0 ? $product['saleprice'] : $product['price'],
                        'delivery_price' => $product['delivery_price'],
                        'currency_id' => $product['currency_id'],
                        'term' => $product['term'],
                        'quantity' => $product['quantity'],
                        'supplier_id' => $product['supplier_id'],
                        'cross' => 2,
                        'excerpt' => $product['excerpt'],
                        'updated_at' => $product['updated_at'],
                        'slug' => $product['slug']
                    ];
                }
            }
        }

        if ($crosses_search) {
            $crosses = $this->product_model->get_search_crosses($crosses_search);
            if ($crosses) {
                foreach ($crosses as $product) {
                    if ($product['prices']) {
                        $filter_brands[] = $product['brand'];
                        foreach ($product['prices'] as $price) {
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
        $this->setH1(sprintf(lang('text_search_search_h1'),$search));
        $this->setTitle(sprintf(lang('text_search_search_title'),$search));
        $this->setDescription(sprintf(lang('text_search_search_description'),$search));
        $this->setKeywords(sprintf(lang('text_search_search_keywords'),$search));

        if (isset($filter_brands)) {
            $data['filter_brands'] = array_unique($filter_brands);
            sort($data['filter_brands'], SORT_STRING);
        }

        $this->load->view('header');
        $this->load->view('search/search', $data);
        $this->load->view('footer');
    }
}
