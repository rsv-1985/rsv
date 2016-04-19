<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Front_controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->language('user');
        $this->load->model('user_model');
        $this->load->helper('form');

    }

    public function index() {
        redirect('autoxadmin');
    }

    public function register() {

        if($this->session->logged_in){
            redirect('autoxadmin');
        }

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
                $save['access'] = 1;
                $user_id = $this->user_model->insert($save);
                $user_info = $this->user_model->get($user_id );
                if($user_info){
                    $newdata = array(
                        'user_id'  => $user_info['id'],
                        'firstname' => $user_info['firstname'],
                        'lastname' => $user_info['lastname'],
                        'email' => $user_info['email'],
                        'access' => $user_info['access']
                    );
                    $this->session->set_userdata($newdata);
                    redirect('autoxadmin');
                }
            }else{
                $data['error'] = validation_errors();
            }
        }
        $this->load->view('admin/user/register', $data);
    }

    public function login() {

        if($this->User_model->is_login()){
            redirect('autoxadmin');
        }

        if($this->User_model->count_all() == 0){
            redirect('autoxadmin/user/register');
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
                        'access' => $user_info['access']
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

}