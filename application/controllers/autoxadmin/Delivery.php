<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/delivery');
        $this->load->model('delivery_model');
        $this->load->model('payment_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/delivery/index');
        $config['total_rows'] = $this->delivery_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['deliveryes'] = $this->delivery_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/delivery/delivery', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        $data['payment_methods'] = $this->payment_model->payment_get_all();
        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('description', lang('text_description'), 'max_length[3000]');
            $this->form_validation->set_rules('price', lang('text_price'), 'numeric');
            $this->form_validation->set_rules('api', lang('text_api'), 'max_length[32]');
            $this->form_validation->set_rules('sort', lang('text_sort'), 'integer');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/delivery/create',$data);
        $this->load->view('admin/footer');
    }

    public function edit($id){
        $data = [];
        $data['delivery'] = $this->delivery_model->delivery_get($id);
        $data['payment_methods'] = $this->payment_model->payment_get_all();
        if(!$data['delivery']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('description', lang('text_description'), 'max_length[3000]');
            $this->form_validation->set_rules('price', lang('text_price'), 'numeric');
            $this->form_validation->set_rules('api', lang('text_api'), 'max_length[32]');
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/delivery/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->delivery_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/delivery');
    }

    private function save_data($id = false){
        $save = [];
        $save['name'] = (string)$this->input->post('name', true);
        $save['description'] = $this->input->post('description', true);
        $save['price'] = (float)$this->input->post('price');
        $save['api'] = (string)$this->input->post('api', true);
        $save['sort'] = (int)$this->input->post('sort', true);
        $save['payment_methods'] = serialize($this->input->post('payment_methods'));
        $save['free_cost'] = (float)$this->input->post('free_cost');
        $id = $this->delivery_model->insert($save, $id);
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/delivery');
        }
    }
}