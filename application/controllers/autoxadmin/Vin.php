<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Vin extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('vinrequest_model');
    }

    public function index()
    {
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/vin/index');
        $config['per_page'] = 30;
        $data['vins'] = $this->vinrequest_model->vin_get_all($config['per_page'], $this->uri->segment(5));
        $config['total_rows'] = $this->vinrequest_model->total_rows;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $this->load->view('admin/header');
        $this->load->view('admin/vin/vin', $data);
        $this->load->view('admin/footer');
    }

    public function edit($id)
    {
        $data = $this->vinrequest_model->get($id);

        if ($this->input->post()) {
            $this->form_validation->set_rules('manufacturer', lang('text_vin_manufacturer'), 'required');
            $this->form_validation->set_rules('model', lang('text_vin_model'), 'required');
            $this->form_validation->set_rules('engine', lang('text_vin_engine'), 'required');
            $this->form_validation->set_rules('parts', lang('text_vin_parts'), 'required');
            $this->form_validation->set_rules('name', lang('text_vin_name'), 'required');
            $this->form_validation->set_rules('telephone', lang('text_vin_telephone'), 'required');
            $this->form_validation->set_rules('email', lang('text_vin_email'), 'required|valid_email');
            if ($this->form_validation->run() == true) {
                $this->load->library('sender');
                if ($this->input->post('send_sms')) {
                    unset($_POST['send_sms']);
                    $this->sender->sms($this->input->post('telephone', true), $this->input->post('comment', true));
                }

                if ($this->input->post('send_email')) {
                    unset($_POST['send_email']);
                    $contacts = $this->settings_model->get_by_key('contact_settings');
                    $this->sender->email('Ответ на VIN запрос ', $this->input->post('email', true), explode(';', $contacts['email']));
                }
                $_POST['updated_at'] = date("Y-m-d H:i:s");
                $this->vinrequest_model->insert($this->input->post(),$id);
                $this->session->set_flashdata('success', 'VIN сохранен');
                redirect('/autoxadmin/vin');
            } else {
                $this->error = validation_errors();
            }
        }

        if (!$data) {
            show_404();
        }

        $this->load->view('admin/header');
        $this->load->view('admin/vin/edit', $data);
        $this->load->view('admin/footer');
    }


    public function delete()
    {
        $this->db->truncate('search_history');
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/report/search_history');
    }
}