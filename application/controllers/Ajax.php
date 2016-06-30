<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends Front_controller{
    public function __construct()
    {
        parent::__construct();
        if(!$this->input->is_ajax_request()){
            redirect('/');
        }
    }
    public function newsletter(){
        $json = [];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[newsletter.email]');
        if ($this->form_validation->run() == true){
            $this->load->model('newsletter_model');
            $this->newsletter_model->insert(['email' => $this->input->post('email', true)]);
            $this->session->set_flashdata('success', 'Newsletter Ok!');
            $json['success'] = true;
        }else{
            $json['error'] = validation_errors();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function call_back(){
        $json = [];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', lang('text_call_back_name'), 'required|trim');
        $this->form_validation->set_rules('telephone', lang('text_call_back_telephone'), 'required|numeric|trim');
        if ($this->form_validation->run() == true)
        {
            $name = $this->input->post('name', true);
            $telephone = $this->input->post('telephone', true);
            $subject = lang('text_call_back_subject');
            $html = lang('text_call_back_name').':'.$name.'<br>';
            $html .= lang('text_call_back_telephone').':'.$telephone.'<br>';
            $this->load->library('sender');
            $this->sender->email($subject, $html, explode(';',$this->contacts['email']), explode(';',$this->contacts['email']));
            $this->session->set_flashdata('success', lang('text_call_back_success'));

            $json['success'] = lang('text_call_back_success');
        }else{
            $json['error'] = validation_errors();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function vin(){
        $json = [];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('manufacturer', lang('text_vin_manufacturer'), 'required');
        $this->form_validation->set_rules('model', lang('text_vin_model'), 'required');
        $this->form_validation->set_rules('engine', lang('text_vin_engine'), 'required');
        $this->form_validation->set_rules('parts', lang('text_vin_parts'), 'required');
        $this->form_validation->set_rules('name', lang('text_vin_name'), 'required');
        $this->form_validation->set_rules('telephone', lang('text_vin_telephone'), 'required|numeric');
        if ($this->form_validation->run() == true)
        {
            $this->load->library('sender');
            $subject = lang('text_vin_subject');
            $html = '';
            foreach($this->input->post() as $key => $value){
                $html .= lang('text_vin_'.$key).':'.$value.'<br>';
            }

            $this->sender->email($subject, $html, explode(';',$this->contacts['email']), explode(';',$this->contacts['email']));

            $json['success'] = lang('text_vin_success');
        }
        else
        {
            $json['error'] = validation_errors();
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function login(){
        $json = [];
        $this->load->language('customer');
        $this->form_validation->set_rules('login', lang('text_login'), 'required|max_length[32]|trim');
        $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
        if ($this->form_validation->run() !== false){
            $login = $this->input->post('login', true);
            $password = $this->input->post('password', true);
            if($this->customer_model->login($login, $password)){
                $this->session->set_flashdata('success', sprintf(lang('text_success_login'),$this->session->customer_name));
                $json['success'] = true;
            }else{
                $json['error'] = lang('text_error');
            }
        }else{
            $json['error'] = validation_errors();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function pre_search(){
        $this->load->model('product_model');
        $search = $this->input->post('search', true);
        $json = [];
        $json['brand'] = $this->product_model->get_pre_search($search);
        $json['search_query'] = $search;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function get_search(){
        $this->load->model('product_model');
        $this->load->language('search');
        $ID_art = $this->input->get('ID_art', true);
        $brand = $this->input->get('brand', true);
        $sku = $this->input->get('sku', true);
        $is_admin = $this->input->get('is_admin');
        $results = $this->product_model->get_search($ID_art, $brand, $sku, true, true);
        if($is_admin){
            $html = $this->load->view('form/admin_result',$results, true);;
        }else{
            $html = $this->load->view('form/result',$results, true);
        }

        $this->output
            ->set_content_type('application/html')
            ->set_output($html);
    }

    public function add_cart(){
        $json = [];
        $json['error'] = lang('text_error_cart');

        $slug = $this->input->post('slug', true);
        $quantity = (int)$this->input->post('quantity');
        $this->load->model('product_model');
        $product = $this->product_model->get_by_slug($slug, false);
        if($product){
            $data = [
                'id'      => $slug,
                'qty'     => (int)$quantity,
                'price'   => (float)$product['saleprice'] > 0 ? $product['saleprice'] : $product['price'],
                'name'    => mb_strlen($product['name']) == 0 ? 'no name' : mb_ereg_replace("[^a-zA-ZА-Яа-я0-9\s]","",$product['name']),
                'sku' => $product['sku'],
                'brand' => $product['brand'],
                'supplier_id' => (int)$product['supplier_id'],
                'is_stock' => (bool)$product['is_stock']
            ];

            if($product['is_stock']){
                $quan_in_cart = key_exists(md5($slug),$this->cart->contents()) ? $this->cart->contents()[md5($slug)]['qty'] : 0;
                if($product['quantity'] < $quantity + $quan_in_cart){
                    $json['error'] = lang('text_error_qty_cart_add');
                    unset($data);
                }
            }
        }

        if(isset($data) && $this->cart->insert($data)){
            $json['success'] = lang('text_success_cart');
            $json['product_count'] = $this->cart->total_items();
            $json['cart_amunt'] = format_currency($this->cart->total());
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

   

  

    public function get_tecdoc_info(){
        $json = [];
        $data = [];
        $data['sku'] = $this->input->post('sku', true);
        $data['brand'] = $this->input->post('brand', true);
        $data['tecdoc_info'] = false;
        $ID_art = $this->tecdoc->getIDart($data['sku'], $data['brand']);
        if(isset($ID_art[0]->ID_art)){
            $info = $this->tecdoc->getArticle($ID_art[0]->ID_art);
            if(isset($info[0])){
                $data['tecdoc_info'] = $info[0];
            }
        }

        $json['html'] = $this->load->view('form/tecdocinfo',$data, true);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
}