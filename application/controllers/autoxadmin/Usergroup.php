<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Usergroup extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/usergroup');
        $this->load->model('usergroup_model');
        $this->load->model('user_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/usergroup/index');
        $config['total_rows'] = $this->usergroup_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['usergroupes'] = $this->usergroup_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/usergroup/usergroup', $data);
        $this->load->view('admin/footer');
    }

    public function create(){

        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[32]|trim');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $data['controllers'] = $this->usergroup_model->get_controllers_name();

        $this->load->view('admin/header');
        $this->load->view('admin/usergroup/create',$data);
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['usergroup'] = $this->usergroup_model->get($id);
        if(!$data['usergroup']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[32]|trim');
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }
        $data['controllers'] = $this->usergroup_model->get_controllers_name();
        $this->load->view('admin/header');
        $this->load->view('admin/usergroup/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $is_used = $this->user_model->count_all(['group_id' => (int)$id]);
        if($is_used > 0){
            $this->session->set_flashdata('error', lang('text_error_delete'));
        }else{
            $this->usergroup_model->delete($id);
            $this->session->set_flashdata('success', lang('text_success'));
        }
        redirect('autoxadmin/usergroup');
    }

    private function save_data($id = false){
        $save = [];
        $save['name'] = $this->input->post('name', true);
        $save['access'] = serialize($this->input->post('access'));
        $id = $this->usergroup_model->insert($save, $id);
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/usergroup');
        }
    }
}