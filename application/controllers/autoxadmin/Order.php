<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/order');
        $this->load->model('order_model');
        $this->load->model('order_product_model');
        $this->load->model('orderstatus_model');
        $this->load->model('payment_model');
        $this->load->model('delivery_model');
        $this->load->model('supplier_model');
        $this->load->model('order_history_model');
        $this->load->model('settings_model');
        $this->load->model('message_template_model');
        $this->load->model('product_model');
        $this->load->library('sender');
    }

    public function products(){
        $data = [];
        $this->load->library('pagination');

        if($this->input->post()){
            $this->form_validation->set_rules('status_id', 'Статус', 'required|integer');
            $this->form_validation->set_rules('slug', 'slug', 'required|trim');
            $this->form_validation->set_rules('order_id', 'order_id', 'required|integer');

            if ($this->form_validation->run() !== false){
                $save = [];
                $save['status_id'] = (int)$this->input->post('status_id');

                $this->order_model->update_item($this->input->post('slug', true), (int)$this->input->post('order_id'),$save);
                $this->session->set_flashdata('success', lang('text_success'));
                redirect('autoxadmin/order/products');
            }else{
                $this->error = validation_errors();
            }
        }

        $config['base_url'] = base_url('autoxadmin/order/products');
        $config['per_page'] = 20;
        $data['products'] = $this->order_model->order_get_all_products($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->order_model->total_rows;
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        $data['status'] = $this->orderstatus_model->status_get_all();
        $data['suppliers'] = $this->supplier_model->supplier_get_all();
        $data['status_totals'] = $this->order_model->get_status_totals($data['status']);

        $this->load->view('admin/header');
        $this->load->view('admin/order/products', $data);
        $this->load->view('admin/footer');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/order/index');
        $config['per_page'] = 10;
        $data['orders'] = $this->order_model->order_get_all($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->order_model->total_rows;
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);


        $data['status'] = $this->orderstatus_model->status_get_all();
        $data['status_totals'] = $this->order_model->get_status_totals($data['status']);
        $data['payment'] = $this->payment_model->payment_get_all();
        $data['delivery'] = $this->delivery_model->delivery_get_all();
        
        $this->load->view('admin/header');
        $this->load->view('admin/order/order', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        
    }

    public function edit($id){

        $data = [];
        $data['order'] = $this->order_model->get($id);
        if(!$data['order']){
            show_404();
        }

        $settings_fraud = $this->settings_model->get_by_key('scamdb');
        $data['scamdb_info'] = false;
        if(@$settings_fraud['access_token']){
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://scamdb.info/ru/v1/fraud/find?search='.$data['order']['telephone'].'&access-token='.$settings_fraud['access_token']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($curl, CURLOPT_TIMEOUT, 2);
            $result = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($result);

            if(is_array($result)){
                $data['scamdb_info'] = '<a href="http://scamdb.info/ru/fraud/'.@$result[0]->id.'" target="_blank">Обнаружен в базе scamdb.info</a>';
            }
        }
        $data['status'] = $this->orderstatus_model->status_get_all();
        $data['payment'] = $this->payment_model->payment_get_all();
        $data['delivery'] = $this->delivery_model->delivery_get_all();
        $data['supplier'] = $this->supplier_model->supplier_get_all();
        $data['history'] = $this->order_history_model->history_get($id);
        $data['products'] = $this->order_product_model->get_all(false, false, ['order_id' => (int)$data['order']['id']]);

        if($this->input->post()){
            $this->form_validation->set_rules('delivery_method', lang('text_delivery_method'), 'required|integer');
            $this->form_validation->set_rules('payment_method', lang('text_payment_method'), 'required|integer');
            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('last_name', lang('text_last_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('patronymic', lang('text_patronymic'), 'max_length[255]');
            $this->form_validation->set_rules('telephone', lang('text_telephone'), 'required|max_length[32]');
            $this->form_validation->set_rules('email', 'email', 'valid_email');
            $this->form_validation->set_rules('comment', lang('text_comment'), 'max_length[3000]');
            $this->form_validation->set_rules('address', lang('text_address'), 'max_length[3000]');
            if ($this->form_validation->run() !== false) {

                $delivery_price = (float)$this->input->post('delivery_price');
                $commissionpay = 0;
                $total = 0;

                if($this->input->post('products')){
                    foreach($this->input->post('products') as $product){
                        $total += $product['quantity'] * $product['price'];
                    }
                }

                $payment_id = (int)$this->input->post('payment_method', true);
                if ($payment_id) {
                    $paymentInfo = $this->payment_model->get($payment_id);
                    if ($paymentInfo['fix_cost'] > 0 || $paymentInfo['comission'] > 0) {
                        if ($paymentInfo['comission'] > 0) {
                            $commissionpay = $paymentInfo['comission'] * ($total + $delivery_price) / 100;
                        }
                        if ($paymentInfo['fix_cost'] > 0) {
                            $commissionpay = $commissionpay + $paymentInfo['fix_cost'];
                        }
                    }
                }


                $total = $total + $delivery_price + $commissionpay;

                $save = [];
                $save['customer_id'] = $this->input->post('customer_id', true);
                $save['first_name'] = $this->input->post('first_name', true);
                $save['last_name'] = $this->input->post('last_name', true);
                $save['patronymic'] = $this->input->post('patronymic', true);
                $save['email'] = $this->input->post('email', true);
                $save['telephone'] = $this->input->post('telephone', true);
                $save['delivery_method_id'] = (int)$this->input->post('delivery_method');
                $save['payment_method_id'] = (int)$this->input->post('payment_method');
                $save['address'] = $this->input->post('address',true);
                $save['total'] = (float)$total;
                $save['created_at'] = date('Y-m-d H:i:s');
                $save['updated_at'] = date('Y-m-d H:i:s');
                $save['status'] = (int)$this->input->post('status', true);
                $save['commission'] = (float)$commissionpay;
                $save['delivery_price'] = (float)$delivery_price;
                $save['paid'] = (bool)$this->input->post('paid', true);
                $order_id = $this->order_model->insert($save, $id);
                //Возвращаем товары на склад если у поставщик отмечено "Наш склад"
                if($data['products']){
                    foreach ($data['products'] as $return_product){
                        $supplier_info = $this->supplier_model->get($return_product['supplier_id']);
                        if($supplier_info['stock']){
                            $this->product_model->update_stock($return_product,'+');
                        }
                    }
                }
                $this->order_product_model->delete_by_order($id);
                if ($order_id) {
                    $products = [];
                    foreach ($this->input->post('products') as $item) {
                        $products[] = [
                            'order_id' => $order_id,
                            'slug' => $item['slug'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'name' => $item['name'],
                            'sku' => $item['sku'],
                            'brand' => $item['brand'],
                            'supplier_id' => $item['supplier_id'],
                            'status_id' => $save['status'] != $data['order']['status'] ? $save['status'] : $item['status_id'],
                            'term' => $item['term']
                        ];
                    }

                    $this->order_product_model->insert_batch($products);
                    $this->session->set_flashdata('success', lang('text_success'));

                    //history
                    if($this->input->post('history')){
                        $history = [];
                        $history['order_id'] = $order_id;
                        $history['date'] = date("Y-m-d H:i:s");
                        $history['text'] = $this->input->post('history', true);
                        $history['send_sms'] = (bool)$this->input->post('send_sms');
                        $history['send_email'] = (bool)$this->input->post('send_email');
                        $history['user_id'] = $this->User_model->is_login();
                        $this->order_history_model->insert($history);
                        
                        if($history['send_email'] && mb_strlen($save['email']) > 0){
                            $contacts = $this->settings_model->get_by_key('contact_settings');
                            $this->sender->email(sprintf(lang('text_email_subject'), $order_id),$history['text'], $save['email'],explode(';',$contacts['email']));
                        }

                        if($history['send_sms'] && mb_strlen($save['telephone']) > 0){
                            $this->sender->sms($save['telephone'],$history['text']);
                        }
                    }
                    //order_status
                    if($save['status'] != $data['order']['status']){
                        $order_info = $save;
                        $order_info['order_id'] = $order_id;
                        $order_info['status'] = $data['status'][$save['status']]['name'];
                        $order_info['payment_method'] = $data['payment'][$save['payment_method_id']]['name'];
                        $order_info['delivery_method'] = $data['delivery'][$save['delivery_method_id']]['name'];
                        //Получаем шаблон сообщения 2 - Смена статуса заказа
                        $message_template = $this->message_template_model->get(2);
                        foreach ($order_info as $field => $value){
                            if(in_array($field,['total','commission','delivery_price'])) $value = format_currency($value);
                            $message_template['subject'] = str_replace('{'.$field.'}',$value, $message_template['subject']);
                            $message_template['text'] = str_replace('{'.$field.'}',$value, $message_template['text']);
                            $message_template['text'] = str_replace('{products}',$this->load->view('email/order', ['products' => $products], true), $message_template['text']);
                            $message_template['text_sms'] = str_replace('{'.$field.'}',$value, $message_template['text_sms']);
                        }

                        //Добавляем историю смены статуса заказа
                        $history = [];

                        $contacts = $this->settings_model->get_by_key('contact_settings');
                        if($save['email'] != ''){
                            $history['send_email'] = true;
                            $this->sender->email($message_template['subject'],$message_template['text'], $save['email'],explode(';',$contacts['email']));
                        }
                        if($save['telephone'] != ''){
                            $history['send_sms'] = true;
                            $this->sender->sms($save['telephone'],$message_template['text_sms']);
                        }


                        $history['order_id'] = $order_id;
                        $history['date'] = date("Y-m-d H:i:s");
                        $history['text'] = $data['status'][$save['status']]['name'];
                        $history['user_id'] = $this->User_model->is_login();
                        $this->order_history_model->insert($history);

                    }
                    redirect('autoxadmin/order/edit/'.$id);
                }
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/order/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->order_model->delete($id);
        $this->order_product_model->delete_by_order($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/order');
    }
    //ajax сумма заказа при редактировании
    public function get_total(){
        $json = [];
        $delivery_price = 0;
        $commissionpay = 0;
        $total = 0;

        if($this->input->post('products')){
            foreach($this->input->post('products') as $product){
                $total += $product['quantity'] * $product['price'];
            }
        }

        $delivery_id = (int)$this->input->post('delivery_method', true);
        if($delivery_id){

            $deliveryInfo = $this->delivery_model->get($delivery_id);
            if($this->input->post('delivery_price')){
                $delivery_price = (float)$this->input->post('delivery_price');
            }else{
                $delivery_price = (float)$deliveryInfo['price'];
            }

            $json['delivery_description'] = $deliveryInfo['description'];
        }


        $payment_id = (int)$this->input->post('payment_method', true);
        if($payment_id){
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

        $json['delivery_price'] = $delivery_price;
        $json['commission'] = $commissionpay;
        $json['subtotal'] = $total;
        $json['total'] = $total + $delivery_price + $commissionpay;


        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function add_product(){
        $this->load->model('product_model');
        $product_id = (int)$this->input->post('product_id');
        $supplier_id = (int)$this->input->post('supplier_id');
        $term = (int)$this->input->post('term');
        $json = $this->product_model->get_product_for_cart($product_id, $supplier_id, $term);
        $json['term'] = format_term($json['term']);
        $json['sup_name'] = $this->supplier_model->suppliers[$json['supplier_id']]['name'];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function search_products(){
        $search = $this->input->post('search', true);
        $products = $this->product_model->get_search_text($search);
        $html = 'Ничего не найдено';
        if($products){
            $html = '<ul class="list-group">';
            foreach ($products as $product){
                $html .= '<li class="list-group-item">'.$this->supplier_model->suppliers[$product['supplier_id']]['name'].' '.$product['name'].' '.$product['sku'].' '.$product['brand'].' '.format_currency($product['price']).' '.format_term($product['term']).'<a href="#" onclick="add_product('.$product['id'].','.$product['supplier_id'].','.$product['term'].')"> Добавить</a> </li>';
            }
            $html .= '</ul>';
        }
        exit($html);
    }
}