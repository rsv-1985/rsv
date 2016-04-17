<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');


class Product extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/product');
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('supplier_model');
        $this->load->model('currency_model');
    }

    public function index(){
        $data = [];

        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/product/index');
        $config['total_rows'] = $this->product_model->product_count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        if($this->input->post()){
            $this->form_validation->set_rules('delivery_price', lang('text_delivery_price'), 'required|numeric|trim');
            $this->form_validation->set_rules('price', lang('text_price'), 'required|numeric|trim');
            $this->form_validation->set_rules('saleprice', lang('text_saleprice'), 'numeric|trim');
            $this->form_validation->set_rules('status', lang('text_status'), 'numeric|trim');
            if ($this->form_validation->run() !== false){
                $save = [];
                $save['delivery_price'] = (float)$this->input->post('delivery_price', true);
                $save['price'] = (float)$this->input->post('price', true);
                $save['saleprice'] = (float)$this->input->post('saleprice', true);
                $save['status'] = (bool)$this->input->post('status', true);
                $this->product_model->update_item($save, $this->input->post('slug'));
                $this->session->set_flashdata('success', lang('text_success'));
                redirect('autoxadmin/product');
            }else{
                $this->error = validation_errors();
            }
        }

        $data['products'] = $this->product_model->admin_product_get_all($config['per_page'], $this->uri->segment(4));
        $data['supplier'] = $this->supplier_model->supplier_get_all();
        $data['currency'] = $this->currency_model->currency_get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/product/product', $data);
        $this->load->view('admin/footer');
    }

    public function delete($slug){
        $product_info = $this->product_model->admin_get_by_slug($slug,false);
        if($product_info){
            if(mb_strlen($product_info['image']) > 0){
                @unlink('./uploads/product/'.$product_info['image']);
            }
        }
        $this->product_model->product_delete($slug);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/product');
    }
}