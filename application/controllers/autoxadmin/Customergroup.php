<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customergroup extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/customergroup');
        $this->load->model('customergroup_model');
        $this->load->model('customer_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/customergroup/index');
        $config['total_rows'] = $this->customergroup_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['customergroupes'] = $this->customergroup_model->get_all($config['per_page'], $this->uri->segment(4));
        $data['types'] = $this->customergroup_model->get_types();
        $this->load->view('admin/header');
        $this->load->view('admin/customergroup/customergroup', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('type', lang('text_type'), 'required|max_length[1]|trim');
            $this->form_validation->set_rules('value', lang('text_value'), 'integer|trim');
            $this->form_validation->set_rules('fix_value', lang('text_fix_value'), 'numeric|trim');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $data = [];
        $data['types'] = $this->customergroup_model->get_types();
        $this->load->view('admin/header');
        $this->load->view('admin/customergroup/create', $data);
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['customergroup'] = $this->customergroup_model->get($id);
        if(!$data['customergroup']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('type', lang('text_type'), 'required|max_length[1]|trim');
            $this->form_validation->set_rules('value', lang('text_value'), 'integer|trim');
            $this->form_validation->set_rules('fix_value', lang('text_fix_value'), 'numeric|trim');
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }
        $data['types'] = $this->customergroup_model->get_types();
        $this->load->view('admin/header');
        $this->load->view('admin/customergroup/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $is_used = $this->customer_model->count_all(['customer_group_id' => (int)$id]);
        if($is_used > 0 || $id == 1){
            $this->session->set_flashdata('error', lang('text_error_delete'));
        }else{
            $this->customergroup_model->delete($id);
            $this->session->set_flashdata('success', lang('text_success'));
        }
        redirect('autoxadmin/customergroup');
    }

    private function save_data($id = false){
        $save = [];
        $save['name'] = $this->input->post('name', true);
        $save['type'] = $this->input->post('type', true);
        $save['value'] = $this->input->post('value', true);
        $save['fix_value'] = $this->input->post('fix_value', true);
        $save['is_default'] = (bool)$this->input->post('is_default', true);
        if($save['is_default']){
            $this->customergroup_model->update('is_default',false);
        }
        $id = $this->customergroup_model->insert($save, $id);
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/customergroup');
        }
    }
}