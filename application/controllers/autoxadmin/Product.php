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

        $config['per_page'] = 10;
        $data['products'] = $this->product_model->admin_product_get_all($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->product_model->total_rows;
        $config['reuse_query_string'] = TRUE;

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

    public function edit($slug){

        $data = [];
        $data['product'] = $this->product_model->admin_get_by_slug($slug);
        if(!$data['product']){
            redirect('autoxadmin/product');
        }

        if($this->input->post()){
            $this->form_validation->set_rules('sku', lang('text_sku'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('brand', lang('text_brand'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('name', lang('text_name'), 'max_length[155]|trim');
            $this->form_validation->set_rules('image', lang('text_image'), 'max_length[255]|trim');
            $this->form_validation->set_rules('h1', lang('text_h1'), 'max_length[250]|trim');
            $this->form_validation->set_rules('title', lang('text_title'), 'max_length[250]|trim');
            $this->form_validation->set_rules('meta_description', lang('text_meta_description'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('meta_keywords', lang('text_meta_keywords'), 'max_length[250]|trim');
            if($this->input->post('slug') != $data['product']['slug']){
                $this->form_validation->set_rules('slug', lang('text_slug'), 'max_length[255]|trim|is_unique[product.slug]');
            }
            $this->form_validation->set_rules('description', lang('text_description'), 'max_length[12000]|trim');
            $this->form_validation->set_rules('excerpt', lang('text_excerpt'), 'max_length[32]|trim');
            $this->form_validation->set_rules('currency_id', lang('text_currency_id'), 'required|integer');
            $this->form_validation->set_rules('delivery_price', lang('text_delivery_price'), 'required|numeric');
            $this->form_validation->set_rules('saleprice', lang('text_saleprice'), 'numeric');
            $this->form_validation->set_rules('price', lang('text_price'), 'required|numeric');
            $this->form_validation->set_rules('quantity', lang('text_quantity'), 'integer');
            $this->form_validation->set_rules('supplier_id', lang('text_supplier_id'), 'required|integer');
            $this->form_validation->set_rules('term', lang('text_term'), 'integer');
            $this->form_validation->set_rules('bought', lang('text_bought'), 'integer');
            $this->form_validation->set_rules('category_id', lang('text_category_id'), 'integer');
            if ($this->form_validation->run() !== false){
                $file_name = $this->input->post('image');
                if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
                    $config['upload_path']          = './uploads/product/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['encrypt_name']         = true;

                    $this->load->library('upload', $config);

                    if($this->upload->do_upload('userfile')){
                        $upload_data = $this->upload->data();
                        $file_name = $upload_data['file_name'];
                        @unlink('./uploads/product/'.$data['product']['image']);
                    }
                    else{
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        redirect('autoxadmin/product/edit/'.$slug);
                    }
                }else{
                    if(mb_strlen($file_name) == 0){
                        @unlink('./uploads/product/'.$data['product']['image']);
                    }
                }
                $this->save_data($slug, $file_name);
            }else{
                $this->error = validation_errors();
            }
        }
        $data['supplier'] = $this->supplier_model->supplier_get_all();
        $data['currency'] = $this->currency_model->currency_get_all();
        $data['category'] = $this->category_model->admin_category_get_all();

        $this->load->view('admin/header');
        $this->load->view('admin/product/edit', $data);
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

    public function save_data($slug, $file_name){
        $save = [];
        $save['sku'] = $this->product_model->clear_sku($this->input->post('sku', true));
        $save['brand'] = $this->product_model->clear_brand($this->input->post('brand', true));
        $save['name'] = $this->input->post('name', true);
        $save['image'] = $file_name;
        $save['h1'] = $this->input->post('h1', true);
        $save['title'] = $this->input->post('title', true);
        $save['meta_description'] = $this->input->post('meta_description', true);
        $save['meta_keywords'] = $this->input->post('meta_keywords', true);
        $save['supplier_id'] = (int)$this->input->post('supplier_id', true);
        if($this->input->post('slug')){
            $save['slug'] = $this->input->post('slug', true);
        }else{
            $save['slug'] = url_title($save['name'].' '.$save['sku'].' '.$save['brand'].' '.$save['supplier_id'], 'dash', true);
        }
        $save['description'] = $this->input->post('description');
        $save['excerpt'] = $this->input->post('excerpt', true);
        $save['currency_id'] = (int)$this->input->post('currency_id', true);
        $save['delivery_price'] = (float)$this->input->post('delivery_price', true);
        $save['saleprice'] = (float)$this->input->post('saleprice', true);
        $save['price'] = (float)$this->input->post('price', true);
        $save['quantity'] = (int)$this->input->post('quantity', true);
        $save['term'] = (int)$this->input->post('term', true);
        $save['updated_at'] = date('Y-m-d H:i:s');
        $save['viewed'] = (int)$this->input->post('viewed', true);
        $save['bought'] = (int)$this->input->post('bought', true);
        $save['category_id'] = (int)$this->input->post('category_id', true);
        $save['status'] = (bool)$this->input->post('category_id', true);
        $this->product_model->update_item($save, $slug);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/product/edit/'.$save['slug']);
    }
}