<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Orderstatus extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/orderstatus');
        $this->load->model('orderstatus_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/orderstatus/index');
        $config['total_rows'] = $this->orderstatus_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['orderstatuses'] = $this->orderstatus_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/orderstatus/orderstatus', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[32]');
            $this->form_validation->set_rules('color', lang('text_color'), 'required|max_length[32]');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/orderstatus/create');
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['orderstatus'] = $this->orderstatus_model->get($id);
        if(!$data['orderstatus']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[32]');
            $this->form_validation->set_rules('color', lang('text_color'), 'required|max_length[32]');
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/orderstatus/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->orderstatus_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/orderstatus');
    }

    private function save_data($id = false){
        $save = [];
        $save['name'] = $this->input->post('name', true);
        $save['color'] = $this->input->post('color', true);
        $save['is_new'] = (bool)$this->input->post('is_new', true);
        if($save['is_new']){
            $this->orderstatus_model->update('is_new',0);
        }
        $save['is_complete'] = (bool)$this->input->post('is_complete', true);
        if($save['is_complete']){
            $this->orderstatus_model->update('is_complete',0);
        }
        $id = $this->orderstatus_model->insert($save, $id);
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/orderstatus');
        }
    }
}