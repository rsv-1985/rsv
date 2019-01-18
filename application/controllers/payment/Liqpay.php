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
        exit('da');
        $this->load->language('payment/liqpay');


        $amount = (float)$this->input->get('amount');
        if($amount > 0){
            $settings = $this->settings_model->get_by_key('liqpay');

            $order_id = rand(1,200);


            $description = "#" . $order_id;
            $result_url = base_url('/payment/liqpay/success');
            $server_url = base_url('/payment/liqpay/callback');

            $private_key = $settings['public_key'];
            $public_key = $settings['merchant_public_key'];

            $currency = $this->currency_model->get_default()['code'];
            if ($currency == 'RUR') {
                $currency = 'RUB';
            }

            $language = 'ru';

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

    public function callback()
    {
        $data = $this->request->post['data'];
        $private_key = $this->config->get('liqpay_signature');
        $signature = $this->calculateSignature($data, $private_key);
        $parsed_data = json_decode(base64_decode($data), true);
        file_put_contents('liqpay.txt',json_encode($parsed_data));
        $order_id = explode('_',$parsed_data['order_id'])[0];

        if ($signature == $this->request->post['signature']) {
            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($order_id);
            if($parsed_data['status'] == 'success'){
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('liqpay_order_status_id'),'Оплата подтверждена автоматически ID'.$parsed_data['payment_id']);
                //Ставим статус оплаты Оплата подтверждена
                $this->db->query("UPDATE ".DB_PREFIX."order SET pay_status_id = '2' WHERE order_id = '".(int)$order_id."'");

                //Создаем оплату
                $this->db->query("INSERT INTO ".DB_PREFIX."pay_history SET 
                   order_id = '".(int)$order_id."',
                   pay_fio = '".$this->db->escape('ID: '.$parsed_data['payment_id'].' Карта: '.$parsed_data['sender_card_mask2'].' Дата:'.$parsed_data['end_date'])."',
                   pay_total = '".(float)$parsed_data['amount']."',
                   pay_date = '".date('Y-m-d')."',
                   pay_time = '".date('H:i')."',
                   created_at = '".date('Y-m-d H:i:s')."',
                    where_paid = 'LiqPay',
                    currency_pay_id = '".(int)$order_info['currency_id']."'
                  ");
            }else{

                $this->model_checkout_order->addOrderHistory($order_id, $order_info['order_status_id'],'Liqpay status: '.$parsed_data['status']);
            }

        }
    }

    private function calculateSignature($data, $private_key)
    {
        return base64_encode(sha1($private_key . $data . $private_key, true));
    }
}
