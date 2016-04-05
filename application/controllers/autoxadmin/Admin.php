<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/user');
        $this->load->model('user_model');
    }

    public function index(){
        if(!$this->session->access){
            redirect('autoxadmin');
        }
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/admin/index');
        $config['total_rows'] = $this->user_model->count_all();
        $config['per_page'] = 10;
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        $data['users'] = $this->user_model->get_all($config['per_page'], $this->uri->segment(4),$this->input->get());

        $this->load->view('admin/header');
        $this->load->view('admin/user/user', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
       $data = [];
        $data['error'] = false;

        if($this->input->post()){
            $this->form_validation->set_rules('firstname', 'firstname', 'required|max_length[32]|trim');
            $this->form_validation->set_rules('lastname', 'lastname', 'required|max_length[32]|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|max_length[128]|valid_email|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]|trim');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|max_length[32]|trim|matches[password]');
            if ($this->form_validation->run() !== false){
                $save = [];
                $save['firstname'] = $this->input->post('firstname', true);
                $save['lastname'] = $this->input->post('lastname', true);
                $save['email'] = $this->input->post('email', true);
                $save['password'] = password_hash($this->input->post('password', true), PASSWORD_BCRYPT);
                $user_id = $this->user_model->insert($save);
                if($user_id){
                    redirect('autoxadmin/admin');
                }
            }else{
                $data['error'] = validation_errors();
            }
        }
        $this->load->view('admin/user/register', $data);
    }

    public function edit($id){

    }

    public function delete($id){
        $this->user_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/admin');
    }
}