<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/newsletter');
        $this->load->model('newsletter_model');
        $this->load->helper('file');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/newsletter/index');
        $config['total_rows'] = $this->newsletter_model->newsletter_count_all();
        $config['per_page'] = 25;

        $this->pagination->initialize($config);

        $data['newsletteres'] = $this->newsletter_model->newsletter_get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/newsletter/newsletter', $data);
        $this->load->view('admin/footer');

    }

    public function delete($id){
        $this->newsletter_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/newsletter');
    }

    public function delete_all(){
        $this->newsletter_model->truncate();
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/newsletter');
    }
    
    public function export(){
        $this->newsletter_model->export_csv();
    }
}