<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/payment');
        $this->load->model('payment_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/payment/index');
        $config['total_rows'] = $this->payment_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['paymentes'] = $this->payment_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/payment/payment', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('description', lang('text_description'), 'max_length[3000]');
            $this->form_validation->set_rules('comission', lang('text_comission'), 'integer');
            $this->form_validation->set_rules('api', lang('text_api'), 'max_length[32]');
            $this->form_validation->set_rules('sort', lang('text_sort'), 'integer');
            $this->form_validation->set_rules('fix_cost', lang('text_fix_cost'), 'numeric');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/payment/create');
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['payment'] = $this->payment_model->get($id);
        if(!$data['payment']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('description', lang('text_description'), 'max_length[3000]');
            $this->form_validation->set_rules('comission', lang('text_comission'), 'integer');
            $this->form_validation->set_rules('api', lang('text_api'), 'max_length[32]');
            $this->form_validation->set_rules('sort', lang('text_sort'), 'integer');
            $this->form_validation->set_rules('fix_cost', lang('text_fix_cost'), 'numeric');
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/payment/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->payment_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/payment');
    }

    private function save_data($id = false){
        $save = [];
        $save['name'] = (string)$this->input->post('name', true);
        $save['description'] = $this->input->post('description', true);
        $save['comission'] = (int)$this->input->post('comission', true);
        $save['api'] = (string)$this->input->post('api', true);
        $save['sort'] = (int)$this->input->post('sort', true);
        $save['fix_cost'] = (float)$this->input->post('fix_cost', true);
        $id = $this->payment_model->insert($save, $id);
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/payment');
        }
    }
}