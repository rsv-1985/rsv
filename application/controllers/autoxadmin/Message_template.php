<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Message_template extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/message_template');
        $this->load->model('message_template_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/message_template/index');
        $config['total_rows'] = $this->message_template_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['templates'] = $this->message_template_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/message_template/message_template', $data);
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['message_template'] = $this->message_template_model->get($id);
        if(!$data['message_template']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('subject', lang('text_subject'), 'required|max_length[255]|trim');
            $this->form_validation->set_rules('text', lang('text_text'), 'required|trim');
            $this->form_validation->set_rules('text', lang('text_text'), 'trim');

            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/message_template/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        if($id > 3){
            $this->message_template_model->delete($id);
            $this->session->set_flashdata('success', lang('text_success'));
        }

        redirect('autoxadmin/message_template');
    }

    private function save_data($id = false){
        $save = [];
        $save['subject'] = $this->input->post('subject', true);
        $save['text'] = $this->input->post('text');
        $save['text_sms'] = $this->input->post('text_sms', true);

        $id = $this->message_template_model->insert($save, $id);
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/message_template');
        }
    }
}