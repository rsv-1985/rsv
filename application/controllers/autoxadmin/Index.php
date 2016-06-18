<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Admin_controller{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->view('admin/header');
        $this->load->view('admin/index');
        $this->load->view('admin/footer');
    }

    public function cache(){
        //Delete mysql cache
        $this->db->cache_delete_all();
        //Delete web cache
        $this->load->helper('file');
        delete_files('./application/cache/web/');
        //Database optimeze
        $this->load->dbutil();
        $this->dbutil->optimize_database();
        $this->session->set_flashdata('success', lang('text_success_cache'));
        redirect('autoxadmin');
    }
}