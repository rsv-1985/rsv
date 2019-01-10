<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Liqpay extends CI_Controller{

    private $version = 3;
    private $action = 'pay';

    public function index(){
        $this->load->language('payment/liqpay');


        $amount = (float)$this->input->get('amount');
        if($amount > 0){
            $order_id = $this->session->data['order_id'];
            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($order_id);
            // Collect info about the order to be sent to the API

            $description = "#" . $order_id;
            $result_url = base_url('/payment/liqpay/success');
            $server_url = base_url('/payment/liqpay/callback');

            $private_key = $this->config->get('liqpay_signature');
            $public_key = $this->config->get('liqpay_merchant');
            $currency = $order_info['currency_code'];
            if ($currency == 'RUR') {
                $currency = 'RUB';
            }
            if($order_info['prepayment'] > 0){
                $amount = $order_info['prepayment'];
                $description .= ' предоплата.';
            }else{
                $amount = $this->currency->format(
                    $order_info['total'],
                    $order_info['currency_code'],
                    $order_info['currency_value'],
                    false
                );
            }

            switch ($order_info['language_id']){
                case 2:
                case 6:
                case 7:
                    $language = 'ru';
                    break;
                case 3:
                    $language = 'uk';
                    break;
                case 5:
                    $language = 'en';
                    break;
                default:
                    $language = 'ru';
                    break;
            }

            $send_data = array('version' => $this->version,
                'public_key' => $public_key,
                'amount' => $amount,
                'currency' => $currency,
                'description' => $description,
                'order_id' => $order_id.'_'.time(),
                'action' => $this->action,
                'server_url' => $server_url,
                'result_url' => $result_url);



            $liqpay_data = base64_encode(json_encode($send_data));
            $liqpay_signature = $this->calculateSignature($liqpay_data, $private_key);

            $data['data'] = $liqpay_data;
            $data['signature'] = $liqpay_signature;
            $data['language'] = $language;
            $data['action'] = 'https://www.liqpay.com/api/3/checkout';

            $data['text_no_close'] = $this->language->get('text_no_close');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/liqpay.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/payment/liqpay.tpl', $data);
            } else {
                return $this->load->view('default/template/payment/liqpay.tpl', $data);
            }
        }else{
            redirect('/');
        }
    }

    public function callback(){

    }
}
