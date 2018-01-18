<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/banner');
        $this->load->model('banner_model');
        $this->load->model('product_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/banner/index');
        $config['total_rows'] = $this->banner_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['banners'] = $this->banner_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/banner/banner', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'max_length[255]|trim');
            $this->form_validation->set_rules('description', lang('text_description'), 'trim');
            $this->form_validation->set_rules('link', lang('text_link'), 'max_length[255]|trim');
            if ($this->form_validation->run() !== false){
                $config['upload_path']          = './uploads/banner/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 10000;
                $config['max_width']            = 2500;
                $config['max_height']           = 1200;
                $config['encrypt_name']         = true;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('userfile'))
                {
                    $upload_data = $this->upload->data();
                    $this->save_data(false, $upload_data['file_name']);
                }
                else
                {
                    $this->error =  $this->upload->display_errors();
                }

            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/banner/create');
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['banner'] = $this->banner_model->get($id);
        if(!$data['banner']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'max_length[255]|trim');
            $this->form_validation->set_rules('description', lang('text_description'), 'trim');
            $this->form_validation->set_rules('link', lang('text_link'), 'max_length[255]|trim');

            if ($this->form_validation->run() !== false){
                if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
                    $config['upload_path']          = './uploads/banner/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['max_size']             = 10000;
                    $config['max_width']            = 2500;
                    $config['max_height']           = 1200;
                    $config['encrypt_name']         = true;

                    $this->load->library('upload', $config);

                    if($this->upload->do_upload('userfile')){
                        $upload_data = $this->upload->data();
                        $file_name = $upload_data['file_name'];
                        @unlink('./uploads/banner/'.$data['banner']['image']);
                    }
                    else{
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        redirect('/autoxadmin/banner');
                    }
                }else{
                    $file_name = $data['banner']['image'];
                }
                $this->save_data($id, $file_name);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/banner/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $data['banner'] = $this->banner_model->get($id);
        if(!$data['banner']){
            show_404();
        }
        @unlink('./uploads/banner/'.$data['banner']['image']);
        $this->banner_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/banner');
    }

    private function save_data($id = false, $file_name){
        $save = [];
        $save['image'] = $file_name;
        $save['name'] = $this->input->post('name', true);
        $save['description'] = $this->input->post('description', true);
        $save['show_slider'] = (bool)$this->input->post('show_slider', true);
        $save['show_box'] = (bool)$this->input->post('show_box', true);
        $save['show_carousel'] = (bool)$this->input->post('show_carousel', true);
        $save['show_product'] = (bool)$this->input->post('show_product', true);
        $save['link'] = $this->input->post('link', true);
        $save['new_window'] = (bool)$this->input->post('new_window', true);
        $save['sort'] = (int)$this->input->post('sort', true);
        $save['status'] = (bool)$this->input->post('status', true);

        $id = $this->banner_model->insert($save, $id);
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/banner');
        }
    }
}