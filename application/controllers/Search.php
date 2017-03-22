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
        $search = $this->input->get('search', true);

        $crosses_search = false;
        if($ID_art){
            $crosses_search = $this->product_model->get_crosses($ID_art, $brand, $search);
        }

        $this->product_model->api_supplier($search, $brand, $crosses_search);

        $data['products'] = false;
        $data['cross'] = false;
        $data['about'] = false;
        $data['brands'] = false;

        $data['brands'] = $this->product_model->get_brands($search);


        if($brand && $search){
            $data['products'] = $this->product_model->get_search_products($search, $brand);

            if($crosses_search){
                $data['cross'] = $this->product_model->get_search_crosses($crosses_search);
            }

        }elseif(!$data['products'] && !$data['brands'] && !$data['cross']){
            $data['about'] = $this->product_model->get_search_text($search);
        }

        $data['h1'] = $search.' '.$brand;


        $this->load->view('header');
        $this->load->view('search/search', $data);
        $this->load->view('footer');
    }
}
