<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/review');
        $this->load->model('review_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/review/index');
        $config['total_rows'] = $this->review_model->count_all();
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $data['reviews'] = $this->review_model->get_all($config['per_page'], $this->uri->segment(4),false, ['status' => 'ASC']);

        $this->load->view('admin/header');
        $this->load->view('admin/review/review', $data);
        $this->load->view('admin/footer');
    }

    public function edit($id){
        $data['review'] = $this->review_model->get($id);

        if($this->input->post()){
            $save = [
                'text' => $this->input->post('text', true),
                'author' => $this->input->post('author', true),
                'status' => (bool)$this->input->post('status')
            ];

            $this->review_model->insert($save, $id);
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('/autoxadmin/review');
        }
        $this->load->view('admin/header');
        $this->load->view('admin/review/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->review_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('/autoxadmin/review');
    }
}