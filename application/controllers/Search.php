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


    public function index()
    {

        $brand = $this->input->get('brand', true);
        $search = strip_tags($this->input->get('search', true));

        $ID_art = (int)$this->input->get('ID_art');

        if (!$ID_art && $brand && $search) {
            $tecdoc_id_art = $this->tecdoc->getIDart($this->product_model->clear_sku($search), $brand);
            if ($tecdoc_id_art) {
                $ID_art = $tecdoc_id_art[0]->ID_art;
            }
        }

        $data['brands'] = $this->product_model->get_brands($search);


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

        if (!$brand && $data['brands']) {
            foreach ($data['brands'] as $item) {
                $crosses_brand = $this->product_model->get_crosses($item['ID_art'], $item['brand'], $item['sku']);
                if ($crosses_brand) {
                    $crosses_search = array_merge($crosses_search, $crosses_brand);
                }
            }
        }

        $cross_suppliers = $this->product_model->api_supplier($this->product_model->clear_sku($search), $brand, $crosses_search);

        if ($cross_suppliers) {
            foreach ($cross_suppliers as $cross_supplier) {
                $crosses_search = array_merge($crosses_search, $cross_supplier);
            }
        }

        $crosses_search = array_unique($crosses_search, SORT_REGULAR);

        if($brand && $search && $crosses_search){
            $autox_array = [];

            foreach ($crosses_search as $cs){
                $autox_array[] = [
                    'code' => $this->product_model->clear_sku($search),
                    'brand' => $this->product_model->clear_brand($brand),
                    'code2' => $cs['sku'],
                    'brand2' => $cs['brand'],
                    'name' => '',
                    'source' => base_url()
                ];
            }

            $url = 'https://autox.pro/cross/add';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($autox_array));
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            print_r($response);
            curl_close($ch);
        }
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

        $data['h1'] = $search . ' ' . $brand;

        if (isset($filter_brands)) {
            $data['filter_brands'] = array_unique($filter_brands);
            sort($data['filter_brands'], SORT_STRING);
        }

        $this->load->view('header');
        $this->load->view('search/search', $data);
        $this->load->view('footer');
    }
}
