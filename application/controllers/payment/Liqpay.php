<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Liqpay extends Front_controller{

    private $version = 3;
    private $action = 'pay';

    public function index(){
        $this->load->language('payment/liqpay');


        $amount = (float)$this->input->get('amount');
        if($amount > 0 && $this->is_login){
            $settings = $this->settings_model->get_by_key('liqpay');

            //Создаем полату
            $this->load->model('customer_pay_model');

            if($settings['commission']){
                $total = round($amount + $amount * $settings['commission'] / 100, 2);
                $order_id = $this->customer_pay_model->create($this->is_login, $amount, 'liqpay +'.(float)$settings['commission'].'% '.$total);
            }else{
                $order_id = $this->customer_pay_model->create($this->is_login, $amount, 'liqpay');
                $total = $amount;
            }


            $description = "Пополнение баланса #" . $order_id;
            $result_url = base_url('/payment/liqpay/success');
            $server_url = base_url('/payment/liqpay/callback');

            $public_key = $settings['public_key'];
            $private_key = $settings['merchant_public_key'];

            $currency = $this->currency_model->get_default()['code'];
            if ($currency == 'RUR') {
                $currency = 'RUB';
            }

            $language = 'ru';



            $send_data = array('version' => $this->version,
                'public_key' => $public_key,
                'amount' => round($total,2),
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

            $data['text_no_close'] = lang('text_no_close');

            $this->load->view('payment/liqpay', $data);

        }else{
            redirect('/');
        }
    }

    public function callback()
    {
        $data = $this->input->post('data');

        $settings = $this->settings_model->get_by_key('liqpay');

        $private_key = $settings['merchant_public_key'];

        $signature = $this->calculateSignature($data, $private_key);

        $parsed_data = json_decode(base64_decode($data), true);

        $pay_id = $parsed_data['order_id'];

        if ($signature == $this->input->post('signature')) {

            $this->load->model('customer_pay_model');
            $this->load->model('customerbalance_model');

            $pay_info = $this->customer_pay_model->get($pay_id);

            if($parsed_data['status'] == 'success'){

                //Меняем статус оплаты
                $this->customer_pay_model->insert(['status_id' => 1], $pay_info['id']);

                //Зачисляем на баланс деньги
                $this->customerbalance_model->add_transaction(
                    $pay_info['customer_id'],
                    (float)$pay_info['amount'],
                    lang('text_customer_pay').' '. (int)$pay_info['id'],
                    1, //Зачисдение
                    $pay_info['transaction_date'],
                    0,//user_id
                    0,//invoice_id
                    $pay_info['id']//pay_id
                );
            }else{
                //Меняем статус оплаты
                $this->customer_pay_model->insert(['status_id' => 2], $pay_info['id']);
            }
        }
    }

    private function calculateSignature($data, $private_key)
    {
        return base64_encode(sha1($private_key . $data . $private_key, true));
    }


    public function success(){
        redirect('/customer');
    }
}
