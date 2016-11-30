<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Synonym extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/synonym');
        $this->load->model('synonym_model');
        $this->load->model('product_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/synonym/index');
        $config['total_rows'] = $this->synonym_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['synonymes'] = $this->synonym_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/synonym/synonym', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('brand1', lang('text_brand1'), 'required|max_length[32]|is_unique[synonym.brand1]');
            $this->form_validation->set_rules('brand2', lang('text_brand2'), 'required|max_length[32]');
           if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/synonym/create');
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['synonym'] = $this->synonym_model->get($id);
        if(!$data['synonym']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('brand1', lang('text_brand1'), 'required|max_length[32]|is_unique[synonym.brand1]|trim');
            $this->form_validation->set_rules('brand2', lang('text_brand2'), 'required|max_length[32]|trim');
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/synonym/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->synonym_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/synonym');
    }

    private function save_data($id = false){
        $this->load->model('product_model');
        $save = [];
        $save['brand1'] = $this->product_model->clear_brand($this->input->post('brand1', true));
        $save['brand2'] = $this->product_model->clear_brand($this->input->post('brand2', true));
        $id = $this->synonym_model->insert($save, $id);
        if($id){
            //Делаем замену бренда у всех товаров
            $this->product_model->update_brand($save['brand1'],$save['brand2']);
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/synonym');
        }
    }
}