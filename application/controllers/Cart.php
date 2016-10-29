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
        $this->load->model('supplier_model');
        $this->load->model('message_template_model');
    }

    public function index(){
        if(@$this->options['order_only_registered'] && !$this->is_login){
            $this->session->set_flashdata('error', lang('error_order_only_registered'));
            redirect('customer/registration');
        }
        $data = [];
        $this->title = lang('text_heading');
        $data['delivery'] = $this->delivery_model->get_all(false, false, false, ['sort' => 'ASC']);
        $data['payment'] = $this->payment_model->get_all(false, false, false, ['sort' => 'ASC']);
        $data['suppliers'] = $this->supplier_model->supplier_get_all();
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
                //Получаем данные способа доставки, если это апи получаем текст для коментария
                $additional_comment = '';
                $deliveryInfo = $this->delivery_model->get((int)$this->input->post('delivery_method'));
                if($deliveryInfo && !empty($deliveryInfo['api'])){
                    $this->load->helper('security');
                    $this->load->add_package_path(APPPATH.'third_party/delivery/'.$deliveryInfo['api'].'/', FALSE);
                    $this->load->library($deliveryInfo['api']);
                    $additional_comment = xss_clean($this->{$deliveryInfo['api']}->get_comment());
                    $this->load->remove_package_path();
                }
                $order_status = $this->orderstatus_model->get_default();
                $cart_data = $this->total_cart();
                $save = [];
                $save['customer_id'] = $this->is_login;
                $save['first_name'] = $this->input->post('first_name', true);
                $save['last_name'] = $this->input->post('last_name', true);
                $save['email'] = $this->input->post('email', true);
                $save['telephone'] = $this->input->post('telephone', true);
                $save['delivery_method_id'] = (int)$this->input->post('delivery_method');
                $save['payment_method_id'] = (int)$this->input->post('payment_method');
                $save['comments'] = $this->input->post('comment', true)."\n".$additional_comment;
                $save['total'] = (float)$cart_data['total'];
                $save['created_at'] = date('Y-m-d H:i:s');
                $save['updated_at'] = date('Y-m-d H:i:s');
                $save['status'] = $order_status['id'];
                $save['commission'] = (float)$cart_data['commissionpay'];
                $save['delivery_price'] = (float)$cart_data['delivery_price'];
                $order_id = $this->order_model->insert($save);
                $save['order_id'] = (int)$order_id;
                if($order_id){
                    $products = [];
                    foreach($this->cart->contents() as $item){
                        $products[] = [
                            'order_id' => $order_id,
                            'slug' => $item['slug'],
                            'quantity' => $item['qty'],
                            'price' => $item['price'],
                            'name' => $item['name'],
                            'sku' => $item['sku'],
                            'brand' => $item['brand'],
                            'supplier_id' => $item['supplier_id'],
                            'status_id' => $order_status['id'],
                            'term' => (int)$item['term']
                        ];
                        
                        $this->product_model->update_bought($item);
                    }
                    $this->order_product_model->insert_batch($products);

                    //Получаем шаблон сообщения 1 - новый заказ
                    $message_template = $this->message_template_model->get(1);
                    foreach ($save as $field => $value){
                        if(in_array($field,['total','commission','delivery_price'])) $value = format_currency($value);
                        $message_template['subject'] = str_replace('{'.$field.'}',$value, $message_template['subject']);
                        $message_template['text'] = str_replace('{'.$field.'}',$value, $message_template['text']);
                        $message_template['text'] = str_replace('{payment_method}',$cart_data['paymentInfo']['name'], $message_template['text']);
                        $message_template['text'] = str_replace('{delivery_method}',$cart_data['deliveryInfo']['name'], $message_template['text']);
                        $message_template['text'] = str_replace('{products}',$this->load->view('email/order', ['products' => $products], true), $message_template['text']);
                        $message_template['text_sms'] = str_replace('{'.$field.'}',$value, $message_template['text_sms']);
                    }
                    $this->load->library('sender');

                    $this->sender->email($message_template['subject'], $message_template['text'], explode(';',$this->contacts['email']),explode(';',$this->contacts['email']));

                    if(strlen($save['email']) > 0){
                        $this->sender->email($message_template['subject'],$message_template['text'], $save['email'],explode(';',$this->contacts['email']));
                    }

                    if(!empty($save['telephone'])){
                        $this->sender->sms($save['telephone'], $message_template['text_sms']);
                    }

                    $this->cart->destroy();
                    
                    //Если это api платежной системы передаем ей управление
                    if(!empty($cart_data['paymentInfo']['api'])){
                        $this->load->add_package_path(APPPATH.'third_party/payment/'.$cart_data['paymentInfo']['api'].'/', FALSE);
                        $this->load->library($cart_data['paymentInfo']['api']);
                        $this->{$cart_data['paymentInfo']['api']}->get_form($order_id);
                    }
                    
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
            $deliveryInfo = $this->delivery_model->delivery_get($delivery_id);
            if($deliveryInfo['api']){
                $this->load->add_package_path(APPPATH.'third_party/delivery/'.$deliveryInfo['api'].'/', FALSE);
                $this->load->library($deliveryInfo['api']);
                $form_data = $this->{$deliveryInfo['api']}->get_form();
                $json['delivery_description'] = $this->load->view('form', $form_data, true);
                $delivery_price = $this->{$deliveryInfo['api']}->delivery_price;
                $this->load->remove_package_path();
            }else{
                if($deliveryInfo['price'] > 0 && $total < $deliveryInfo['free_cost']){
                    $delivery_price = $deliveryInfo['price'];
                }
                $json['delivery_description'] = $deliveryInfo['description'];
            }
            //Связка способов доставки с способами оплаты
            $json['link_payments'] = $deliveryInfo['payment_methods'];
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
                'total' => $total + $delivery_price + $commissionpay
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

    public function success($order_id = false){
        if(!$order_id){
            show_404();
        }

        $orderInfo = $this->order_model->get($order_id);
        if(!$orderInfo){
            show_404();
        }

        $paymentInfo = $paymentInfo = $this->payment_model->get($orderInfo['payment_method_id']);
        
        if(!$paymentInfo){
            show_404();
        }

        //Если это api платежной системы передаем ей управление
        if(!empty($paymentInfo['api'])){
            $this->load->add_package_path(APPPATH.'third_party/payment/'.$paymentInfo['api'].'/', FALSE);
            $this->load->library($paymentInfo['api']);
            $this->{$paymentInfo['api']}->success($orderInfo);
        }
        
        
    }

    public function clear_cart(){
        $this->cart->destroy();
        redirect('cart');
    }

    public function add_cart()
    {
        $json = [];
        $json['error'] = lang('text_error_cart');

        $product_id = (int)$this->input->post('product_id');
        $supplier_id = (int)$this->input->post('supplier_id');
        $term = (int)$this->input->post('term');
        $quantity = (int)$this->input->post('quantity');

        $this->load->model('product_model');
        $product = $this->product_model->get_product_for_cart($product_id,$supplier_id,$term);
        if ($product) {
           $cartId = $product_id.$supplier_id.$term;
            $data = [
                'id' => $cartId,
                'qty' => (int)$quantity,
                'slug' => $product['slug'],
                'price' => (float)$product['saleprice'] > 0 ? $product['saleprice'] : $product['price'],
                'name' => mb_strlen($product['name']) == 0 ? 'no name' : mb_ereg_replace("[^a-zA-ZА-Яа-я0-9\s]", "", $product['name']),
                'sku' => $product['sku'],
                'brand' => $product['brand'],
                'product_id' => (int)$product['id'],
                'supplier_id' => (int)$product['supplier_id'],
                'term' => (int)$product['term'],
                'is_stock' => (bool)$product['stock']
            ];

            if ($product['stock']) {
                $quan_in_cart = key_exists($cartId, $this->cart->contents()) ? $this->cart->contents()[$cartId]['qty'] : 0;
                if ($product['quantity'] < $quantity + $quan_in_cart) {
                    $json['error'] = lang('text_error_qty_cart_add');
                    unset($data);
                }
            }
        }

        if (isset($data) && $this->cart->insert($data)) {
            $json['cartId'] = $cartId;
            $json['success'] = lang('text_success_cart');
            $json['product_count'] = $this->cart->total_items();
            $json['cart_amunt'] = format_currency($this->cart->total());
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
}