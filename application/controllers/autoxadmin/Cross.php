<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Cross extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/cross');
        $this->load->model('cross_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/cross/index');
        $config['total_rows'] = $this->cross_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['crosses'] = $this->cross_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/cross/cross', $data);
        $this->load->view('admin/footer');
    }
}