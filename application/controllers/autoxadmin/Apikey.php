<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Apikey extends Admin_controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/apikey');
        $this->load->model('keys_model');
        $this->load->model('customer_model');
        $this->load->helper('string');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/apikey/index');
        $config['per_page'] = 10;
        $data['keys'] = $this->keys_model->keys_get_all($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->keys_model->total_rows;


        $this->pagination->initialize($config);



        $this->load->view('admin/header');
        $this->load->view('admin/apikey/keys', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        $data['random_key'] = random_string('sha1');
        if($this->input->post()){
            $this->form_validation->set_rules('login', lang('text_login'), 'required|callback_username_check');
            $this->form_validation->set_rules('key', lang('text_key'), 'required|max_length[40]');
            $this->form_validation->set_rules('key', lang('text_key'), 'is_unique[keys.key]');

            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/apikey/create',$data);
        $this->load->view('admin/footer');
    }

    public function edit($id){
        $data['keydata'] = $this->keys_model->keys_get($id);
        if(!$data['keydata']){
            redirect('autoxadmin');
        }
        if($this->input->post()){
            $this->form_validation->set_rules('login', lang('text_login'), 'required|callback_username_check');
            $this->form_validation->set_rules('key', lang('text_key'), 'required|max_length[40]');
            if($this->input->post('key') != $data['keydata']['key']){
                $this->form_validation->set_rules('key', lang('text_key'), 'is_unique[keys.key]');
            }

            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/apikey/edit',$data);
        $this->load->view('admin/footer');
    }

    public function username_check($str)
    {
        if (!$this->customer_model->getByLogin($str))
        {
            $this->form_validation->set_message('username_check', 'Customer '.$str.' not find');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    private function save_data($id = false){
        $customerInfo = $this->customer_model->getByLogin($this->input->post('login', true));
        $save['user_id'] = (int)$customerInfo['id'];
        $save['key'] = $this->input->post('key',true);
        $save['level'] = (int)$this->input->post('level');
        $save['ip_addresses'] = $this->input->post('ip_addresses');
        $save['ignore_limits'] = (bool)$this->input->post('ignore_limits');
        $save['is_private_key'] = (bool)$this->input->post('is_private_key');
        $this->keys_model->insert($save,$id);
        redirect('autoxadmin/apikey');
    }

    public function delete($id){
        $this->keys_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));

        redirect('autoxadmin/apikey');
    }
}