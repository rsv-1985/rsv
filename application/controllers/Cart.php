<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends Front_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('cart');
        $this->load->model('delivery_model');
        $this->load->model('payment_model');
        $this->load->model('product_model');
        $this->load->model('order_model');
        $this->load->model('order_product_model');
        $this->load->model('orderstatus_model');
    }

    public function index(){
        $data = [];
        $this->title = lang('text_heading');
        $data['delivery'] = $this->delivery_model->get_all(false, false, false, ['sort' => 'ASC']);
        $data['payment'] = $this->payment_model->get_all(false, false, false, ['sort' => 'ASC']);
        if($this->is_login){
            $data['customer'] = $this->customer_model->get($this->is_login);
        }
        if($this->input->post()){
            $this->form_validation->set_rules('delivery_method', lang('text_delivery_method'), 'required|integer');
            $this->form_validation->set_rules('payment_method', lang('text_payment_method'), 'required|integer');
            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('last_name', lang('text_last_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('telephone', lang('text_telephone'), 'required|max_length[32]|numeric');
            $this->form_validation->set_rules('email', 'email', 'valid_email');
            $this->form_validation->set_rules('comment', lang('text_comment'), 'max_length[3000]');
            if ($this->form_validation->run() !== false){
                $order_status = $this->orderstatus_model->get_default();
                $cart_data = $this->total_cart();
                $save = [];
                $save['customer_id'] = $this->is_login;
                $save['first_name'] = $this->input->post('first_name', true);
                $save['last_name'] = $this->input->post('last_name', true);
                $save['email'] = $this->input->post('email', true);
                $save['telephone'] = $this->input->post('telephone', true);
                $save['delivery_method_id'] = $this->input->post('delivery_method', true);
                $save['payment_method_id'] = $this->input->post('payment_method', true);
                $save['comments'] = $this->input->post('comment', true);
                $save['total'] = (float)$cart_data['total'];
                $save['created_at'] = date('Y-m-d H:i:s');
                $save['updated_at'] = date('Y-m-d H:i:s');
                $save['status'] = $order_status['id'];
                $save['commission'] = (float)$cart_data['commissionpay'];
                $save['delivery_price'] = (float)$cart_data['delivery_price'];
                $order_id = $this->order_model->insert($save);
                if($order_id){
                    $products = [];
                    foreach($this->cart->contents() as $item){
                        $products[] = [
                            'order_id' => $order_id,
                            'slug' => $item['id'],
                            'quantity' => $item['qty'],
                            'price' => $item['price'],
                            'name' => $item['name'],
                            'sku' => $item['sku'],
                            'brand' => $item['brand'],
                            'supplier_id' => $item['supplier_id'],
                            'status_id' => $order_status['id']
                        ];
                        
                        $this->product_model->update_bought($item);
                    }
                    $this->order_product_model->insert_batch($products);

                    $email_html = $this->load->view('email/order', [
                        'order_id' => $order_id,
                        'total' => $cart_data['total'],
                        'payment' =>$cart_data['paymentInfo']['name'],
                        'delivery' => $cart_data['deliveryInfo']['name'],
                        'products' => $products
                    ], true);

                    $this->load->library('sender');

                    $this->sender->email(sprintf(lang('text_email_subject'), $order_id), $email_html, explode(';',$this->contacts['email']),explode(';',$this->contacts['email']));
                    if(strlen($save['email']) > 0){
                        $this->sender->email(sprintf(lang('text_email_subject'), $order_id),$email_html, $save['email'],explode(';',$this->contacts['email']));
                    }
                    if(!empty($save['telephone'])){
                        $text = sprintf(lang('text_success_order'), $order_id);
                        $this->sender->sms($save['telephone'], $text);
                    }
                    $this->cart->destroy();
                    $this->session->set_flashdata('success', sprintf(lang('text_success_order'), $order_id));
                    redirect('/');
                }
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('header');
        $this->load->view('cart/cart', $data);
        $this->load->view('footer');
    }

    public function total_cart(){
        $json = [];
        $delivery_price = 0;
        $commissionpay = 0;
        $total = $this->cart->total();

        $json['delivery_description'] = '';
        $delivery_id = (int)$this->input->post('delivery_method', true);
        if($delivery_id){
            $this->load->model('delivery_model');
            $deliveryInfo = $this->delivery_model->get($delivery_id);
            if($deliveryInfo['api']){
                $this->load->add_package_path(APPPATH.'third_party/delivery/'.$deliveryInfo['api'].'/', FALSE);
                $this->load->library($deliveryInfo['api']);
                $form_data = $this->{$deliveryInfo['api']}->get_form();
                $json['delivery_description'] = $this->load->view('form', $form_data, true);
                
                $delivery_price = $this->{$deliveryInfo['api']}->delivery_price;
                $this->load->remove_package_path();
            }else{
                if($deliveryInfo['price'] > 0){
                    $delivery_price = $deliveryInfo['price'];
                }
                $json['delivery_description'] = $deliveryInfo['description'];
            }
        }

        $json['payment_description'] = '';
        $payment_id = (int)$this->input->post('payment_method', true);
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
        if($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($json));
        }else{
            return [
                'delivery_price' => $delivery_price,
                'deliveryInfo' => $deliveryInfo,
                'commissionpay' => $commissionpay,
                'paymentInfo' => $paymentInfo,
                'total' => $total
            ];
        }
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

    public function remove_cart(){
        $json=[];
        $rowid = $this->input->post('key', true);
        $this->cart->remove($rowid);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
}