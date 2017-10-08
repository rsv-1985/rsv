<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Search_history extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/report/search_history');
        $this->load->model('search_history_model');
        $this->load->model('customer_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/report/search_history/index');
        $config['per_page'] = 30;
        $data['search_history'] = $this->search_history_model->search_history_get_all($config['per_page'], $this->uri->segment(5));
        $config['total_rows'] = $this->search_history_model->total_rows;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $this->load->view('admin/header');
        $this->load->view('admin/report/search_history/search_history', $data);
        $this->load->view('admin/footer');
    }

    public function delete(){
        $this->db->truncate('search_history');
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/report/search_history');
    }
}