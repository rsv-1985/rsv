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

                $delivery_price = 0;
                $commissionpay = 0;
                $total = $this->cart->total();

                $delivery_id = (int)$this->input->post('delivery_method', true);
                if($delivery_id){
                    $this->load->model('delivery_model');
                    $deliveryInfo = $this->delivery_model->get($delivery_id);
                    if($deliveryInfo['price'] > 0){
                        $delivery_price = $deliveryInfo['price'];
                    }
                }

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
                }


                $total = $total + $delivery_price + $commissionpay;

                $save = [];
                $save['customer_id'] = $this->is_login;
                $save['first_name'] = $this->input->post('first_name', true);
                $save['last_name'] = $this->input->post('last_name', true);
                $save['email'] = $this->input->post('email', true);
                $save['telephone'] = $this->input->post('telephone', true);
                $save['delivery_method_id'] = $this->input->post('delivery_method', true);
                $save['payment_method_id'] = $this->input->post('payment_method', true);
                $save['comments'] = $this->input->post('comment', true);
                $save['total'] = (float)$total;
                $save['created_at'] = date('Y-m-d H:i:s');
                $save['updated_at'] = date('Y-m-d H:i:s');
                $save['status'] = $order_status['id'];
                $save['commission'] = (float)$commissionpay;
                $save['delivery_price'] = (float)$delivery_price;
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
                            'supplier_id' => $item['supplier_id']
                        ];
                        
                        $this->product_model->update_bought($item['id']);
                    }
                    $this->order_product_model->insert_batch($products);

                    $email_html = $this->load->view('email/order', [
                        'order_id' => $order_id,
                        'total' => $total,
                        'payment' => $paymentInfo['name'],
                        'delivery' => $deliveryInfo['name'],
                        'products' => $products
                    ], true);

                    $this->load->library('sender');

                    $this->sender->email(sprintf(lang('text_email_subject'), $order_id), $email_html, explode(';',$this->contacts['email']),explode(';',$this->contacts['email']));
                    if(strlen($save['email']) > 0){
                        $this->sender->email(sprintf(lang('text_email_subject'), $order_id), $save['email'],explode(';',$this->contacts['email']));
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
}