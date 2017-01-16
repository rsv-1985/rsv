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

        $ID_art = $this->input->get('ID_art', true);
        $brand = $this->input->get('brand', true);
        $sku = $this->input->get('sku', true);

        $is_admin = $this->input->get('is_admin');
        $data['products'] = false;
        $data['cross'] = false;
        $data['about'] = false;
        $data = $this->product_model->get_search($ID_art, $brand, $sku, true, true);


        $min_price = 0;
        $min_price_cross = 0;
        $min_term = 0;
        $data['min_price'] = false;
        $data['min_price_cross'] = false;
        $data['min_term'] = false;

        $data['h1'] = $sku.' '.$brand;
        $data['brands'] = $this->product_model->get_pre_search($sku);

        if ($data['products']) {
            foreach ($data['products'] as $product) {
                if ($product['price'] <= $min_price || !$data['min_price']) {
                    $data['min_price'] = $product;
                    $min_price = $product['saleprice'] > 0 ? $product['saleprice'] : $product['price'];
                }
                if ($product['term'] / 24 <= $min_term || !$data['min_term']) {
                    $data['min_term'] = $product;
                    $min_term = $product['term'] / 24;
                }
            }
        }

        if ($data['cross']) {
            foreach ($data['cross'] as $product) {
                if ($product['price'] <= $min_price_cross || !$data['min_price_cross']) {
                    $data['min_price_cross'] = $product;
                    $min_price_cross = $product['saleprice'] > 0 ? $product['saleprice'] : $product['price'];
                }
                if ($product['term'] / 24 <= $min_term || !$data['min_term']) {
                    $product['is_cross'] = true;
                    $data['min_term'] = $product;
                    $min_term = $product['term'] / 24;
                }
            }
        }

        $this->load->view('header');
        $this->load->view('search/search', $data);
        $this->load->view('footer');
    }
}
