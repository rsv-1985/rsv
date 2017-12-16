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
        $data['new_order'] = $this->db->where(['status' => $this->orderstatus_model->get_default()['id']])->count_all_results('order');
        $data['new_vin'] = $this->db->where(['status' => 0])->count_all_results('vin');
        $data['new_customer'] = $this->db->where(['status' => false])->count_all_results('customer');
        $this->load->view('admin/header');
        $this->load->view('admin/index',$data);
        $this->load->view('admin/footer');
    }

    public function cache(){
        $this->clear_cache();
        $this->session->set_flashdata('success', lang('text_success_cache'));
        redirect('autoxadmin');
    }
}