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
    public $default_currency;
    public $user_access;
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
        $this->default_currency = $this->currency_model->get_default();
        $this->new_customer = $this->db->where(['status' => false])->count_all_results('customer');

        $this->load->model('orderstatus_model');
        $this->new_order = $this->db->where(['status' => $this->orderstatus_model->get_default()['id']])->count_all_results('order');

        $this->load->model('usergroup_model');
        $this->user_access = $this->usergroup_model->get_access($this->session->user_group_id);
        $class_name = strtolower(get_called_class());
        if($this->user_access && $class_name != 'index'  &&  !in_array($class_name,$this->user_access)){
            $this->session->set_flashdata('error', lang('text_access_denied'));
            redirect('autoxadmin');
        }
    }

    public function clear_cache($file_name = false){
        $path = $this->config->item('cache_path');
        $cache_path = ($path == '') ? APPPATH.'cache/' : $path;

        if($file_name){
           @unlink($cache_path.$file_name);
        }else{
            //Delete mysql cache
            $this->db->cache_delete_all();
            //Delete web cache


            $handle = opendir($cache_path);
            while (($file = readdir($handle))!== FALSE)
            {
                if ($file != '.htaccess' && $file != 'index.html' && $file != '.gitignore')
                {
                    @unlink($cache_path.'/'.$file);
                }
            }
            closedir($handle);
        }
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
    public $category;
    public $contacts;
    public $options;
    public $garage;
    public $rel_next;
    public $rel_prev;
    public $canonical;

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