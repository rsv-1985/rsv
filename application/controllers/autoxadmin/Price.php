<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Price extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/price');
        $this->load->model('product_model');
        $this->load->model('supplier_model');
        $this->load->model('category_model');
    }

    public function index(){
        $data = [];
        $data['suppliers'] = $this->supplier_model->supplier_get_all();
        $data['categories'] = $this->category_model->admin_category_get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/price/price', $data);
        $this->load->view('admin/footer');
    }
}