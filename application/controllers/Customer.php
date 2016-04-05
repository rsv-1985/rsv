<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Front_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('customer');
        $this->load->model('customergroup_model');
        $this->load->model('order_model');
        $this->load->model('orderstatus_model');
        $this->load->library('pagination');
    }

    public function index(){
        $this->customer_model->is_login('/customer/login');
        $data = [];
        $config['base_url'] = base_url('customer/index');
        $config['total_rows'] = $this->order_model->count_all(['customer_id' => $this->is_login]);
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $data['orders'] = $this->order_model->get_all($config['per_page'], $this->uri->segment(3),['customer_id' => $this->is_login]);
        $data['status'] = $this->orderstatus_model->status_get_all();
        $data['customer'] = $this->customer_model->get($this->is_login);
        $data['customer_group'] = $this->customergroup_model->get($data['customer']['customer_group_id']);

        if($this->input->post()){
            if($this->input->post('login') != $data['customer']['login']){
                $this->form_validation->set_rules('login', lang('text_login'), 'required|max_length[32]|trim|is_unique[customer.login]');
            }
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
                $save = [];
                $save['login'] = $this->input->post('login', true);
                $save['customer_group_id'] = (int)$data['customer']['customer_group_id'];
                $save['first_name'] = $this->input->post('first_name', true);
                $save['second_name'] = $this->input->post('second_name', true);
                $save['address'] = $this->input->post('address', true);
                $save['email'] = $this->input->post('email', true);
                $save['phone'] = $this->input->post('phone', true);
                if($this->input->post('password')){
                    $save['password'] = password_hash($this->input->post('password', true), PASSWORD_BCRYPT);
                }

                $save['updated_at'] = date("Y-m-d H:i:s");

                $save['status'] = true;
                $id = $this->customer_model->insert($save, $data['customer']['id']);
                if($id){
                    $this->session->set_flashdata('success', lang('text_success'));
                    redirect('customer');
                }
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('header');
        $this->load->view('customer/customer', $data);
        $this->load->view('footer');
    }

    public function registration(){
        if($this->is_login){
            redirect('/customer');
        }

        if($this->input->post()){
            $this->form_validation->set_rules('login', lang('text_login'), 'required|max_length[32]|trim|is_unique[customer.login]');
            $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
            $this->form_validation->set_rules('confirm_password', lang('text_confirm_password'), 'required|trim|matches[password]');

            if ($this->form_validation->run() !== false){
                $this->save_data();
                if($this->customer_model->login($this->input->post('login', true),$this->input->post('password', true))){
                    $this->session->set_flashdata('success', sprintf(lang('text_success_login'), $this->session->customer_name));
                    redirect('/');
                }else{
                    $this->session->set_flashdata('error', 'ERROR REGISTRATION');
                    redirect('/');
                }
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('header');
        $this->load->view('customer/registration');
        $this->load->view('footer');
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect('/');
    }

    public function login(){
        if($this->is_login){
            redirect('/customer');
        }
    }

    private function save_data($id = false){
        $save = [];
        $save['customer_group_id'] = (int)$this->customergroup_model->get_default();
        $save['login'] = $this->input->post('login', true);
        $save['password'] =  password_hash($this->input->post('password', true), PASSWORD_BCRYPT);
        $save['created_at'] = date('Y-m-d H:i:s');
        $save['status'] = $this->config->item('active_new_customer');
        $this->customer_model->insert($save, $id);
    }
}