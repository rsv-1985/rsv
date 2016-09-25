<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_controller extends CI_Controller{
    public $error = false;
    public $success = false;
    public $new_customer = false;
    public $new_order = false;

    public function __construct()
    {
        parent::__construct();
        $this->User_model->is_login('autoxadmin/user/login');
        if($this->session->flashdata('error')){
            $this->error = $this->session->flashdata('error');
        }
        if($this->session->flashdata('success')){
            $this->success = $this->session->flashdata('success');
        }

        $this->new_customer = $this->db->where(['status' => false])->count_all_results('customer');
        $this->load->model('orderstatus_model');
        $this->new_order = $this->db->where(['status' => $this->orderstatus_model->get_default()['id']])->count_all_results('order');
    }
}

class Front_controller extends CI_Controller{

    public $title;
    public $description;
    public $keywords;
    public $header_page;
    public $footer_page;
    public $default_currency;
    public $is_login;
    public $is_admin;
    public $success;
    public $error;
    public $category = false;
    public $contacts;
    public $options;
    public $garage;

    public function __construct()
    {
        parent::__construct();
        $this->default_currency = $this->currency_model->get_default();
        $this->header_page = $this->page_model->get_header_page();
        $this->footer_page = $this->page_model->get_footer_page();
        $this->is_login = $this->customer_model->is_login();
        $this->is_admin = $this->User_model->is_login();
        $this->garage = unserialize($this->input->cookie('garage'));

        if($this->session->flashdata('error')){
            $this->error = $this->session->flashdata('error');
        }
        if($this->session->flashdata('success')){
            $this->success = $this->session->flashdata('success');
        }
        
        $this->category = $this->category_model->category_get_all();
        $this->contacts = $this->settings_model->get_by_key('contact_settings');
        $this->options = $this->settings_model->get_by_key('options');

    }
}