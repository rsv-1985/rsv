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
        $config['total_rows'] = $this->search_history_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['search_history'] = [];

        $search_history = $this->search_history_model->get_all($config['per_page'], $this->uri->segment(5),false,['id' => 'DESC']);
        if($search_history){
            foreach ($search_history as $sh){

                $customer_info = $this->customer_model->get($sh['customer_id']);

                $data['search_history'][] = [
                    'customer' => $customer_info ? '<a target="_blank" href="/autoxadmin/customer/edit/'.$customer_info['id'].'">'.$customer_info['first_name'].' '.$customer_info['second_name'].'</a>' : '---',
                    'sku' => $sh['sku'],
                    'brand' => $sh['brand'],
                    'created_at' => $sh['created_at']
                ];
            }
        }

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