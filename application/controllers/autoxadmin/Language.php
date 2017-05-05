<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends Admin_controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('language_model');
        $this->load->language('admin/language');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/language/index');
        $config['total_rows'] = $this->language_model->language_count_all();
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $data['languages'] = $this->language_model->language_get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/language/language', $data);
        $this->load->view('admin/footer');
    }

    public function update(){
        $id = (int)$this->input->post('id');
        $text = $this->input->post('text', true);
        if($id && $text){
            $this->language_model->insert(['text' => $text],$id);
            exit('success');
        }else{
            exit('error');
        }

    }
}