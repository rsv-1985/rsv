<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Currency extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/currency');
        $this->load->model('currency_model');
        $this->load->model('product_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/currency/index');
        $config['total_rows'] = $this->currency_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['currencyes'] = $this->currency_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/currency/currency', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[32]');
            $this->form_validation->set_rules('code', lang('text_code'), 'required|max_length[3]');
            $this->form_validation->set_rules('symbol_left', lang('text_symbol_left'), 'max_length[12]');
            $this->form_validation->set_rules('symbol_right', lang('text_symbol_right'), 'max_length[12]');
            $this->form_validation->set_rules('decimal_place', lang('text_decimal_place'), 'required|max_length[1]|integer');
            $this->form_validation->set_rules('value', lang('text_value'), 'required|numeric');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/currency/create');
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['currency'] = $this->currency_model->get($id);
        if(!$data['currency']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[32]');
            $this->form_validation->set_rules('code', lang('text_code'), 'required|max_length[3]');
            $this->form_validation->set_rules('symbol_left', lang('text_symbol_left'), 'max_length[12]');
            $this->form_validation->set_rules('symbol_right', lang('text_symbol_right'), 'max_length[12]');
            $this->form_validation->set_rules('decimal_place', lang('text_decimal_place'), 'required|max_length[1]|integer');
            $this->form_validation->set_rules('value', lang('text_value'), 'required|numeric');
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/currency/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $is_used = $this->db->where('currency_id',(int)$id)->count_all_results('product_price');
        if($is_used > 0){
            $this->session->set_flashdata('error', lang('text_error_delete'));
        }else{
            $this->currency_model->delete($id);
            $this->session->set_flashdata('success', lang('text_success'));
        }
        redirect('autoxadmin/currency');
    }

    private function save_data($id = false){
        $save = [];
        $save['name'] = $this->input->post('name', true);
        $save['code'] = $this->input->post('code', true);
        $save['symbol_left'] = $this->input->post('symbol_left', true);
        $save['symbol_right'] = $this->input->post('symbol_right', true);
        $save['decimal_place'] = $this->input->post('decimal_place', true);
        $save['value'] = $this->input->post('value', true);
        $save['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->currency_model->insert($save, $id);
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/currency');
        }
    }
}