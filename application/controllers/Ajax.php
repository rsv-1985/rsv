<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends Front_controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            redirect('/');
        }
    }

    public function remove_garage()
    {
        $key = $this->input->post('key');
        unset($this->garage[$key]);

        $cookie = array(
            'name' => 'garage',
            'value' => serialize($this->garage),
            'expire' => 60 * 60 * 24 * 365 * 10,
        );

        $this->input->set_cookie($cookie);
    }

    public function newsletter()
    {
        $json = [];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[newsletter.email]');
        if ($this->form_validation->run() == true) {
            $this->load->model('newsletter_model');
            $this->newsletter_model->insert(['email' => $this->input->post('email', true)]);
            $this->session->set_flashdata('success', 'Newsletter Ok!');
            $json['success'] = true;
        } else {
            $json['error'] = validation_errors();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function call_back()
    {
        $json = [];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', lang('text_call_back_name'), 'required|trim');
        $this->form_validation->set_rules('telephone', lang('text_call_back_telephone'), 'required|trim');
        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name', true);
            $telephone = $this->input->post('telephone', true);
            $subject = lang('text_call_back_subject');
            $html = lang('text_call_back_name') . ':' . $name . '<br>';
            $html .= lang('text_call_back_telephone') . ':' . $telephone . '<br>';
            $this->load->library('sender');
            $this->sender->email($subject, $html, explode(';', $this->contacts['email']), explode(';', $this->contacts['email']));
            $this->session->set_flashdata('success', lang('text_call_back_success'));

            $json['success'] = lang('text_call_back_success');
        } else {
            $json['error'] = validation_errors();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function vin()
    {
        $json = [];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('manufacturer', lang('text_vin_manufacturer'), 'required');
        $this->form_validation->set_rules('model', lang('text_vin_model'), 'required');
        $this->form_validation->set_rules('engine', lang('text_vin_engine'), 'required');
        $this->form_validation->set_rules('parts', lang('text_vin_parts'), 'required');
        $this->form_validation->set_rules('name', lang('text_vin_name'), 'required');
        $this->form_validation->set_rules('telephone', lang('text_vin_telephone'), 'required');
        $this->form_validation->set_rules('email', lang('text_vin_email'), 'required|valid_email');
        if ($this->form_validation->run() == true) {
            $this->load->library('sender');
            $subject = lang('text_vin_subject');
            $html = '';
            foreach ($this->input->post() as $key => $value) {
                $html .= lang('text_vin_' . $key) . ':' . $value . '<br>';
            }

            $this->sender->email($subject, $html, explode(';', $this->contacts['email']),$this->input->post('email',true));

            $json['success'] = lang('text_vin_success');
        } else {
            $json['error'] = validation_errors();
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function login()
    {
        $json = [];
        $this->load->language('customer');
        $this->form_validation->set_rules('login', lang('text_login'), 'required|max_length[32]|trim');
        $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
        if ($this->form_validation->run() !== false) {
            $login = $this->input->post('login', true);
            $password = $this->input->post('password', true);
            if ($this->customer_model->login($login, $password)) {
                $this->session->set_flashdata('success', sprintf(lang('text_success_login'), $this->session->customer_name));
                $json['success'] = true;
            } else {
                $json['error'] = lang('text_error');
            }
        } else {
            $json['error'] = validation_errors();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function pre_search()
    {
        $this->load->model('product_model');
        $search = $this->input->post('search', true);
        $search_type = (int)$this->input->post('search_type');
        $json = [];
        $json['brand'] = $this->product_model->get_brands($search);
        $json['search_query'] = $search;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function get_brands()
    {
        $this->load->model('product_model');
        $search = $this->input->post('search',true);
        $json['brands'] = $this->product_model->get_brands($search);

        $this->output
            ->set_content_type('application/html')
            ->set_output(json_encode($json));
    }

    public function get_tecdoc_info()
    {
        $json = [];
        $data = [];
        $data['sku'] = $this->input->post('sku', true);
        $data['brand'] = $this->input->post('brand', true);
        $data['tecdoc_info'] = false;
        $ID_art = $this->tecdoc->getIDart($data['sku'], $data['brand']);
        if (isset($ID_art[0]->ID_art)) {
            $info = $this->tecdoc->getArticle($ID_art[0]->ID_art);
            if (isset($info[0])) {
                $data['tecdoc_info'] = $info[0];
            }
        }

        $json['html'] = $this->load->view('form/tecdocinfo', $data, true);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
}