<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/customer');
        $this->load->model('customer_model');
        $this->load->model('customergroup_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/customer/index');
        $config['total_rows'] = $this->customer_model->customer_count_all();
        $config['per_page'] = 10;
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        $data['customeres'] = $this->customer_model->customer_get_all($config['per_page'], $this->uri->segment(4),$this->input->get());
        $data['customergroup'] = $this->customergroup_model->get_group();

        $this->load->view('admin/header');
        $this->load->view('admin/customer/customer', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('login', lang('text_login'), 'required|max_length[32]|trim|is_unique[customer.login]');
            $this->form_validation->set_rules('customer_group_id', lang('text_customer_group_id'), 'required|integer|trim');
            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'max_length[32]|trim');
            $this->form_validation->set_rules('second_name', lang('text_second_name'), 'max_length[32]|trim');
            $this->form_validation->set_rules('address', lang('text_address'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('email', lang('text_email'), 'valid_email|trim');
            $this->form_validation->set_rules('phone', lang('text_phone'), 'numeric|trim');
            $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
            $this->form_validation->set_rules('confirm_password', lang('text_confirm_password'), 'required|trim|matches[password]');

            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $data = [];
        $data['customergroup'] = $this->customergroup_model->get_group();

        $this->load->view('admin/header');
        $this->load->view('admin/customer/create', $data);
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['customer'] = $this->customer_model->get($id);
        if(!$data['customer']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('login', lang('text_login'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('customer_group_id', lang('text_customer_group_id'), 'required|integer|trim');
            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'max_length[32]|trim');
            $this->form_validation->set_rules('second_name', lang('text_second_name'), 'max_length[32]|trim');
            $this->form_validation->set_rules('address', lang('text_address'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('email', lang('text_email'), 'valid_email|trim');
            $this->form_validation->set_rules('phone', lang('text_phone'), 'numeric|trim');
            if($this->input->post('password')){
                $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
                $this->form_validation->set_rules('confirm_password', lang('text_confirm_password'), 'required|trim|matches[password]');
            }
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }
        $data['customergroup'] = $this->customergroup_model->get_group();
        $this->load->view('admin/header');
        $this->load->view('admin/customer/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->customer_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/customer');
    }

    private function save_data($id = false){
        $save = [];
        $save['login'] = $this->input->post('login', true);
        $save['customer_group_id'] = (int)$this->input->post('customer_group_id', true);
        $save['first_name'] = $this->input->post('first_name', true);
        $save['second_name'] = $this->input->post('second_name', true);
        $save['address'] = $this->input->post('address', true);
        $save['email'] = $this->input->post('email', true);
        $save['phone'] = $this->input->post('phone', true);
        if($this->input->post('password')){
            $save['password'] = password_hash($this->input->post('password', true), PASSWORD_BCRYPT);
        }
        if($id){
            $save['updated_at'] = date("Y-m-d H:i:s");
        }else{
            $save['created_at'] = date("Y-m-d H:i:s");
            $save['updated_at'] = date("Y-m-d H:i:s");
        }
       $save['status'] = (bool)$this->input->post('status', true);
        $id = $this->customer_model->insert($save, $id);
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/customer');
        }
    }
}