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
