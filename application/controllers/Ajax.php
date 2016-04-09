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
            exit();
        }
    }

    public function vin(){
        $json = [];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('manufacturer', lang('text_vin_manufacturer'), 'required');
        $this->form_validation->set_rules('model', lang('text_vin_model'), 'required');
        $this->form_validation->set_rules('engine', lang('text_vin_engine'), 'required');
        $this->form_validation->set_rules('parts', lang('text_vin_parts'), 'required');
        $this->form_validation->set_rules('name', lang('text_vin_parts'), 'required');
        $this->form_validation->set_rules('telephone', lang('text_vin_parts'), 'required|numeric');
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
        $slug = $this->input->post('slug', true);
        $quantity = (int)$this->input->post('quantity', true);
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
                'supplier_id' => (int)$product['supplier_id']
            ];

            if($this->cart->insert($data)){
                $json['success'] = lang('text_success_cart');
                $json['product_count'] = $this->cart->total_items();
                $json['cart_amunt'] = format_currency($this->cart->total());
            }else{
                $json['error'] = lang('text_error_cart');
            }
        }else{
            $json['error'] = lang('text_error_cart');
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function remove_cart(){
        $json=[];
        $rowid = $this->input->post('key', true);
        $this->cart->remove($rowid);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function update_cart(){
        $json=[];
        $rowid = $this->input->post('key', true);
        $quan = (int)$this->input->post('quan', true);
        $data = [
            'rowid' => $rowid,
            'qty' => $quan
        ];
        $this->cart->update($data);
        $cart = $this->cart->contents();
        if(isset($cart[$rowid])){
            $json['product_subtotal'] = format_currency($cart[$rowid]['subtotal']);
        }
        $json['subtotal'] = format_currency($this->cart->total());
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function total_cart(){
        $json = [];
        $delivery_price = 0;
        $commissionpay = 0;
        $total = $this->cart->total();

        $json['delivery_description'] = '';
        $delivery_id = (int)$this->input->post('delivery_id', true);
        if($delivery_id){
            $this->load->model('delivery_model');
            $deliveryInfo = $this->delivery_model->get($delivery_id);
            if($deliveryInfo['price'] > 0){
                $delivery_price = $deliveryInfo['price'];
            }
            $json['delivery_description'] = $deliveryInfo['description'];
        }

        $json['payment_description'] = '';
        $payment_id = (int)$this->input->post('payment_id', true);
        if($payment_id){
            $this->load->model('payment_model');
            $paymentInfo = $this->payment_model->get($payment_id);
            if($paymentInfo['fix_cost'] > 0 || $paymentInfo['comission'] > 0){

                if($paymentInfo['comission'] > 0){
                    $commissionpay = $paymentInfo['comission'] * ($total + $delivery_price) / 100;
                }
                if($paymentInfo['fix_cost'] > 0){
                    $commissionpay = $commissionpay + $paymentInfo['fix_cost'];
                }
            }
            $json['payment_description'] = $paymentInfo['description'];
        }

        $json['delivery_price'] = format_currency($delivery_price);
        $json['commissionpay'] = format_currency($commissionpay);
        $json['subtotal'] = format_currency($this->cart->total());
        $json['total'] = format_currency($total + $delivery_price + $commissionpay);
        $json['total_items'] = $this->cart->total_items();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function get_tecdoc_info(){
        $json = [];
        $data = [];
        $json['html'] = $this->load->view('form/tecdocinfo',$data, true);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
}