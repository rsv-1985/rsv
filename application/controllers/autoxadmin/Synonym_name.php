<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class synonym_name extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/synonym_name');
        $this->load->model('synonym_name_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/synonym_name/index');
        $config['total_rows'] = $this->synonym_name_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['synonym_names'] = $this->synonym_name_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/synonym_name/synonym_name', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[255]|is_unique[synonym_name.name]|trim');
            $this->form_validation->set_rules('name2', lang('text_name2'), 'required|max_length[255]|trim');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/synonym_name/create');
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['synonym_name'] = $this->synonym_name_model->get($id);
        if(!$data['synonym_name']){
            show_404();
        }
        if($this->input->post()){
            if($this->input->post('name') != trim($data['synonym_name']['name'])){
                $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[255]|is_unique[synonym_name.name]|trim');
            }else{
                $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[255]|trim');
            }

            $this->form_validation->set_rules('name2', lang('text_name2'), 'required|max_length[255]|trim');
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/synonym_name/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->synonym_name_model->delete($id);
        redirect('autoxadmin/synonym_name');
    }

    private function save_data($id = false){
        $this->load->model('product_model');
        $save = [];
        $save['name'] = $this->input->post('name', true);
        $save['name2'] = $this->input->post('name2', true);
        $id = $this->synonym_name_model->insert($save, $id);
        if($id){
            //Делаем замену наименования у всех товаров
            $this->synonym_name_model->update_name($save['name'],$save['name2']);
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/synonym_name');
        }
    }
}