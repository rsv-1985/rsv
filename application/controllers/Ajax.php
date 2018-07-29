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

    public function get_customer_by_phone(){
        $this->load->model('customer_model');
        $phone = $this->input->post('telephone', true);
        $customer_info = $this->customer_model->getByPhone($phone);

        if($customer_info){
            if($this->is_admin){
                $is_login = $this->customer_model->login($customer_info['phone'],'', true);
                if($is_login){
                    exit('is_login');
                }else{
                    exit('error');
                }
            }else{
                exit('true');
            }
        }
        exit('false');
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

    public function cookie_modal(){
        $this->load->helper('cookie');
        $cookie = array(
            'name'   => $this->important_news['modalmessage_cookie_name'],
            'value'  => true,
            'expire' => time() + 60 * 60 * 24 * 30 * 12,
        );

        set_cookie($cookie);
    }

    public function get_manufacturer_year(){
        $html = '<option>---</option>';
        $year = $this->input->post('year');
        $manufacturers = $this->tecdoc->getManufacturerYear($year);
        $settings_tecdoc_manufacturer = $this->settings_model->get_by_key('tecdoc_manufacturer');
        if($manufacturers){
            if($settings_tecdoc_manufacturer){
                foreach ($manufacturers as $item){
                    if(isset($settings_tecdoc_manufacturer[url_title($item->Name)]) && @$settings_tecdoc_manufacturer[url_title($item->Name)]['status']){
                        $name = $settings_tecdoc_manufacturer[url_title($item->Name)]['name'] ? $settings_tecdoc_manufacturer[url_title($item->Name)]['name'] : $item->Name;
                        $html .= '<option value="'.$item->ID_mfa.'">'.$name.'</option>';
                    }
                }
            }else{
                foreach ($manufacturers as $manufacturer){
                    $html .= '<option value="'.$manufacturer->ID_mfa.'">'.$manufacturer->Name.'</option>';
                }
            }

        }
        exit($html);
    }

    public function get_model_year(){
        $ID_mfa = (int)$this->input->post('ID_mfa');
        $year = $this->input->post('year');
        $models = $this->tecdoc->getModelYear($ID_mfa,$year);
        $html = '<option>---</option>';
        if($models){
            foreach ($models as $model){
                $html .= '<option value="'.$model->ID_mod.'">'.$model->Name.'</option>';
            }
        }
        exit($html);
    }

    public function get_typ_year(){

        $ID_mod = (int)$this->input->post('ID_mod');
        $year = $this->input->post('year');
        $types = $this->tecdoc->getTypeYear($ID_mod, $year);
        $html = '<option>---</option>';
        if($types){
            foreach ($types as $typ){
                $html .= '<option value="'.$typ->ID_typ.'">'.$typ->Name.'('.$typ->Engines.' '.$typ->Fuel.')</option>';
            }
        }
        exit($html);
    }

    public function get_tree(){
        $ID_mfa = (int)$this->input->post('ID_mfa');
        $ID_mod = (int)$this->input->post('ID_mod');
        $ID_typ = (int)$this->input->post('ID_typ');

        $slug = [];
        $manufacturer_info = $this->tecdoc->getManufacturer($ID_mfa);
        if($manufacturer_info){
            $slug[] = url_title($manufacturer_info[0]->Name).'_'.$ID_mfa;
        }

        $model_info = $this->tecdoc->getModel($ID_mfa,$ID_mod);

        if($model_info){
            $slug[] = url_title($model_info[0]->Name).'_'.$ID_mod;
        }

        $type_info = $this->tecdoc->getType($ID_mod,$ID_typ);
        if($type_info){
            $slug[] = url_title($type_info[0]->Name).'_'.$ID_typ;
        }
        exit(base_url('catalog/'.implode('/',$slug)));
    }

    public function get_model(){
        $ID_mfa = (int)$this->input->post('ID_mfa');
        $models = $this->tecdoc->getModel($ID_mfa);
        $html = '';
        if($models){
            foreach ($models as $model){
                $html .= '<option value="'.$model->ID_mod.'">'.$model->Name.'</option>';
            }
        }
        exit($html);
    }

    public function get_typ(){
        $ID_mod = (int)$this->input->post('ID_mod');
        $types = $this->tecdoc->getType($ID_mod);
        $html = '';
        if($types){
            foreach ($types as $typ){
                $html .= '<option value="'.$typ->ID_typ.'">'.$typ->Name.'</option>';
            }
        }
        exit($html);
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
            $this->load->model('vinrequest_model');
            $save = [];
            $save['customer_id'] = $this->is_login;
            $save['manufacturer'] = $this->input->post('manufacturer', true);
            $save['model'] = $this->input->post('model', true);
            $save['engine'] = $this->input->post('engine', true);
            $save['vin'] = $this->input->post('vin', true);
            $save['name'] = $this->input->post('name', true);
            $save['telephone'] = $this->input->post('telephone', true);
            $save['email'] = $this->input->post('email', true);
            $save['parts'] = $this->input->post('parts', true);
            $save['status'] = 0;
            $save['created_at'] = date("Y-m-d H:i:s");
            $this->vinrequest_model->insert($save);
            $json['success'] = lang('text_vin_success');
        } else {
            $json['error'] = validation_errors();
        }

        $this->output
            ->set_content_type('application/json')
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

    public function fastorder(){
        $json = [];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Имя', 'trim');
        $this->form_validation->set_rules('telephone', 'Телефон', 'required|trim');
        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name', true);
            $telephone = $this->input->post('telephone', true);
            $href = base_url($this->input->post('href'));
            $subject = 'Быстрый заказ';
            $html = 'Имя:' . $name . '<br>';
            $html .= 'Телефон:' . $telephone . '<br>';
            $html .= 'Товар:' . $href . '<br>';
            $this->load->library('sender');
            $this->sender->email($subject, $html, explode(';', $this->contacts['email']), explode(';', $this->contacts['email']));
            $this->session->set_flashdata('success', 'Заказ отправлен!');

            $json['success'] = true;
        } else {
            $json['error'] = validation_errors();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function goToProduct(){
        $_SESSION['supplier_id'] = (int)$this->input->post('supplier_id');
        $_SESSION['term'] = (int)$this->input->post('term');
    }
}