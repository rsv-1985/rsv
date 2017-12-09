<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_order extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/report/sale_order');
        $this->load->model('report/sale_order_model');
        $this->load->model('orderstatus_model');
    }

    public function index(){
        $data['statuses'] = $this->orderstatus_model->get_all();
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/report/sale_order/index');
        $config['per_page'] = 50;
        $data['sale_orders'] = $this->sale_order_model->sale_order_get_all($config['per_page'], (int)$this->uri->segment(5));
        $config['total_rows'] = $this->sale_order_model->total_rows;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $this->load->view('admin/header');
        $this->load->view('admin/report/sale_order/sale_order', $data);
        $this->load->view('admin/footer');
    }
}