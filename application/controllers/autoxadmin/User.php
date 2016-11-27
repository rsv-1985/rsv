<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Front_controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->language('admin/user');
        $this->load->language('user');
        $this->load->model('user_model');
        $this->load->helper('form');
        $this->load->model('usergroup_model');
    }

    public function index(){
        $this->access();
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
        $this->access();
        $data = [];
        $data['error'] = false;
        $data['user_group'] = $this->usergroup_model->get_all();
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
                $save['group_id'] = (int)$this->input->post('group_id');
                $user_id = $this->user_model->insert($save);
                if($user_id){
                    redirect('autoxadmin/user');
                }
            }else{
                $data['error'] = validation_errors();
            }
        }
        $this->load->view('admin/user/register', $data);
    }

    public function edit($id){
        $this->access();
        $data['user_info'] = $this->user_model->get($id);
        $data['error'] = false;
        if(!$data['user_info']){
            show_404();
        }
        $data['user_group'] = $this->usergroup_model->get_all();
        if($this->input->post()){
            $this->form_validation->set_rules('firstname', 'firstname', 'required|max_length[32]|trim');
            $this->form_validation->set_rules('lastname', 'lastname', 'required|max_length[32]|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|max_length[128]|valid_email|trim');
            if($this->input->post('password')){
                $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]|trim');
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|max_length[32]|trim|matches[password]');
            }
            if ($this->form_validation->run() !== false){
                $save = [];
                $save['firstname'] = $this->input->post('firstname', true);
                $save['lastname'] = $this->input->post('lastname', true);
                $save['email'] = $this->input->post('email', true);
                if($this->input->post('password')){
                    $save['password'] = password_hash($this->input->post('password', true), PASSWORD_BCRYPT);
                }
                $save['group_id'] = (int)$this->input->post('group_id');
                $user_id = $this->user_model->insert($save,$id);
                if($user_id){
                    redirect('autoxadmin/user');
                }
            }else{
                $data['error'] = validation_errors();
            }
        }
        $this->load->view('admin/user/edit', $data);
    }

    public function login() {

        if($this->User_model->is_login()){
            redirect('autoxadmin');
        }

        if($this->User_model->count_all() == 0){
            redirect('autoxadmin/user/create');
        }

        $data = [];
        $data['error'] = false;

        $this->load->helper('form');


        if($this->input->post()){
            // set validation rules
            $this->form_validation->set_rules('email', lang('text_email'), 'trim|required|valid_email');
            $this->form_validation->set_rules('password', lang('text_password'), 'trim|required');

            if ($this->form_validation->run() !== false) {
                $email = $this->input->post('email', true);
                $password = $this->input->post('password',true);
                $user_info = $this->User_model->login($email, $password);
                if($user_info){
                    $newdata = array(
                        'user_id'  => $user_info['id'],
                        'firstname' => $user_info['firstname'],
                        'lastname' => $user_info['lastname'],
                        'email' => $user_info['email'],
                        'access' => $user_info['access'],
                        'user_group_id' => $user_info['group_id']
                    );
                    $this->session->set_userdata($newdata);
                    redirect('autoxadmin');
                }else{
                    $data['error'] = lang('text_login_error');
                }
            } else {
                $data['error'] = validation_errors();
            }
        }

        $this->load->view('admin/user/login',$data);


    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('autoxadmin');
    }

    public function delete($id){
        $this->access();
        $this->user_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/admin');
    }

    public function access(){
        $this->load->model('usergroup_model');
        $user_access = $this->usergroup_model->get_access($this->session->user_group_id);
        $class_name = strtolower(get_called_class());
        if($user_access &&  !in_array($class_name,$user_access)){
            $this->session->set_flashdata('error', lang('text_access_denied'));
            redirect('autoxadmin');
        }
    }

}