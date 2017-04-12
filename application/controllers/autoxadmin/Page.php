<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/page');
        $this->load->model('page_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/page/index');
        $config['total_rows'] = $this->page_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['pages'] = $this->page_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/page/page', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        $data = [];
        if($this->input->post()){
            if(empty($_POST['slug'])){
                $_POST['slug'] = url_title($this->input->post('name', true));
            }
            $this->form_validation->set_rules('parent_id', lang('text_parent_id'), 'integer');
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[255]|trim');
            $this->form_validation->set_rules('h1', lang('text_h1'), 'max_length[255]|trim');
            $this->form_validation->set_rules('title', lang('text_name'), 'max_length[255]|trim');
            $this->form_validation->set_rules('meta_description', lang('text_meta_description'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('meta_keywords', lang('text_meta_keywords'), 'max_length[255]|trim');
            $this->form_validation->set_rules('menu_title', lang('text_menu_title'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('description', lang('text_description'), 'trim');
            $this->form_validation->set_rules('slug', lang('text_slug'), 'is_unique[page.slug]|max_length[255]|trim');
            $this->form_validation->set_rules('sort', lang('text_sort'), 'integer');

            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $data['pages'] = $this->page_model->get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/page/create',$data);
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['page'] = $this->page_model->get($id);
        if(!$data['page']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('parent_id', lang('text_parent_id'), 'integer');
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[255]|trim');
            $this->form_validation->set_rules('title', lang('text_name'), 'max_length[255]|trim');
            $this->form_validation->set_rules('h1', lang('text_h1'), 'max_length[255]|trim');
            $this->form_validation->set_rules('meta_description', lang('text_meta_description'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('meta_keywords', lang('text_meta_keywords'), 'max_length[255]|trim');
            $this->form_validation->set_rules('menu_title', lang('text_menu_title'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('description', lang('text_description'), 'trim');
            if($data['page']['slug'] != $this->input->post('slug')){
                $this->form_validation->set_rules('slug', lang('text_slug'), 'is_unique[page.slug]|max_length[255]|trim');
            }
            $this->form_validation->set_rules('sort', lang('text_sort'), 'integer');

            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }
        $data['pages'] = $this->page_model->get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/page/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->page_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/page');
    }

    private function save_data($id = false){
        $save = [];
        $save['parent_id'] = (int)$this->input->post('parent_id', true);
        $save['name'] = $this->input->post('name', true);
        $save['h1'] = $this->input->post('h1', true);
        $save['title'] = strip_tags($this->input->post('title', true));
        $save['meta_description'] = strip_tags($this->input->post('meta_description', true));
        $save['meta_keywords'] = strip_tags($this->input->post('meta_keywords', true));
        $save['menu_title'] = strip_tags($this->input->post('menu_title', true));
        $save['description'] = $this->input->post('description');
        if($this->input->post('slug', true)){
            $save['slug'] = $this->input->post('slug', true);
        }else{
            $save['slug'] = url_title($this->input->post('name', true),'dash',true);
        }
        if($id){
            $save['updated_at'] = date('Y-m-d H:i:s');
        }else{
            $save['created_at'] = date('Y-m-d H:i:s');
            $save['updated_at'] = date('Y-m-d H:i:s');
        }
        $save['link'] = $this->input->post('link', true);
        $save['new_window'] = (bool)$this->input->post('new_window');
        $save['show_footer'] = (bool)$this->input->post('show_footer');
        $save['show_for_user'] = (bool)$this->input->post('show_for_user');
        $save['sort'] = (int)$this->input->post('sort');
        $save['status'] = (bool)$this->input->post('status');

        $id = $this->page_model->insert($save, $id);
        $this->clear_cache('header_page');
        $this->clear_cache('footer_page');
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/page');
        }
    }
}