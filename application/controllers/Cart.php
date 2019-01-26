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
        $this->load->model('cart_model');
        $this->load->helper('cookie');
        $this->load->library('sender');
    }

    public function index(){
        //Возвразаем отложенные товары в корзину
        if(isset($_SESSION['deferred'])){
            foreach ($_SESSION['deferred'] as $deferred){
                $this->cart->insert($deferred);
            }
            unset($_SESSION['deferred']);
        }

        if($this->input->post('deferred')){
            foreach ($this->cart->contents() as $item){
                if(!in_array($item['rowid'],$_POST['deferred'])){
                    $_SESSION['deferred'][] = $item;
                    $this->cart->remove($item['rowid']);
                }
            }
            redirect('/cart/ordering');
        }
        $this->load->view('header');
        $this->load->view('cart/pre_cart');
        $this->load->view('footer');
    }

    public function ordering(){
        if(@$this->options['order_only_registered'] && !$this->is_login){
            $this->session->set_flashdata('error', lang('error_order_only_registered'));
            redirect('customer/registration');
        }
        $data = [];

        $data['terms_of_use'] = $this->settings_model->get_by_key('terms_of_use');

        $this->setTitle(lang('text_cart_heading'));
        $this->setH1(lang('text_cart_heading'));

        $data['delivery'] = $this->delivery_model->get_all(false, false, false, ['sort' => 'ASC']);
        $data['payment'] = $this->payment_model->get_all(false, false, false, ['sort' => 'ASC']);
        $data['suppliers'] = $this->supplier_model->supplier_get_all();


        if ($this->is_login){

            $customer = $this->customer_model->get($this->is_login);

            if($customer){
                $data['customer'] = [
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['second_name'],
                    'patronymic' => $customer['patronymic'],
                    'telephone' => $customer['phone'],
                    'payment_method_id' => $customer['payment_method_id'],
                    'delivery_method_id' => $customer['delivery_method_id'],
                    'email' => $customer['email'],
                    'address' => $customer['address'],
                    'additional_information' => unserialize($customer['additional_information'])
                ];
            }
        }

        if($this->input->post() && $this->cart->total_items() > 0){
            $_POST['telephone'] = format_phone($_POST['telephone']);

            if(!$this->is_login){
                $this->form_validation->set_rules('telephone', lang('text_telephone'), 'is_unique[customer.telephone]');
                $this->form_validation->set_rules('email', 'Email', 'is_unique[customer.email]|valid_email');
            }

            $this->form_validation->set_rules('delivery_method', lang('text_delivery_method'), 'required|integer|trim');
            $this->form_validation->set_rules('payment_method', lang('text_payment_method'), 'required|integer|trim');
            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'required|max_length[250]|trim');
            $this->form_validation->set_rules('last_name', lang('text_last_name'), 'required|max_length[250]|trim');
            $this->form_validation->set_rules('patronymic', lang('text_patronymic'), 'max_length[255]|trim');
            $this->form_validation->set_rules('telephone', lang('text_telephone'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('email', 'email', 'valid_email|max_length[96]|trim');
            $this->form_validation->set_rules('comment', lang('text_comment'), 'max_length[3000]');
            $this->form_validation->set_rules('address', lang('text_address'), 'max_length[3000]');
            if ($this->form_validation->run() !== false){
                $order_status = $this->orderstatus_model->get_default();
                $cart_data = $this->total_cart();
                $save = [];
                if($this->is_login){
                    $save['customer_id'] = $this->is_login;
                }else{
                    $save['customer_id'] = $this->auto_registration();
                }

                $save['first_name'] = $this->input->post('first_name', true);
                $save['last_name'] = $this->input->post('last_name', true);
                $save['patronymic'] = $this->input->post('patronymic', true);
                $save['email'] = $this->input->post('email', true);
                $save['telephone'] = $this->input->post('telephone', true);
                $save['delivery_method_id'] = (int)$this->input->post('delivery_method');
                $save['payment_method_id'] = (int)$this->input->post('payment_method');
                $save['address'] = $this->input->post('address', true);
                $save['comments'] = $this->input->post('comment', true);
                $save['total'] = (float)$cart_data['total'];
                $save['created_at'] = date('Y-m-d H:i:s');
                $save['updated_at'] = date('Y-m-d H:i:s');
                $save['status'] = $order_status['id'];
                $save['commission'] = (float)$cart_data['commissionpay'];
                $save['delivery_price'] = (float)$cart_data['delivery_price'];
                $order_id = $this->order_model->insert($save);
                if($order_id){

                    $deliveryInfo = $this->delivery_model->get((int)$save['delivery_method_id']);

                    if($deliveryInfo && !empty($deliveryInfo['api'])){
                        $this->load->library('delivery/'.$deliveryInfo['api']);
                        $this->{$deliveryInfo['api']}->save_form($order_id);
                    }

                    $products = [];
                    foreach($this->cart->contents() as $item){

                        $products[] = [
                            'order_id' => $order_id,
                            'product_id' => $item['product_id'],
                            'slug' => $item['slug'],
                            'quantity' => $item['qty'],
                            'delivery_price' => $item['delivery_price'] * $item['qty'],
                            'price' => format_currency($item['price'],false),
                            'name' => $item['name'],
                            'sku' => $item['sku'],
                            'brand' => $item['brand'],
                            'supplier_id' => $item['supplier_id'],
                            'status_id' => $order_status['id'],
                            'term' => (int)$item['term'],
                            'excerpt' => (string)$item['excerpt']
                        ];

                        if($item['is_stock']){
                            $this->product_model->update_stock(
                                [
                                    'product_id' => $item['product_id'],
                                    'supplier_id' => $item['supplier_id'],
                                    'term' => $item['term'],
                                    'quantity' => $item['qty']
                                ],
                                '-');
                        }
                        
                        $this->product_model->update_bought($item);
                    }
                    $this->order_product_model->insert_batch($products);

                    //Получаем шаблон сообщения 1 - новый заказ
                    $message_template = $this->message_template_model->get(1);
                    $save['order_id'] = $order_id;
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

                    if(strlen($save['email']) > 0){
                        $this->sender->email($message_template['subject'],$message_template['text'], $save['email'],explode(';',$this->contacts['email']));
                    }

                    //Для администратора
                    //Получаем шаблон сообщения 1 - новый заказ
                    $message_template = $this->message_template_model->get(1);
                    foreach ($save as $field => $value){
                        if(in_array($field,['total','commission','delivery_price'])) $value = format_currency($value);
                        $message_template['subject'] = str_replace('{'.$field.'}',$value, $message_template['subject']);
                        $message_template['text'] = str_replace('{'.$field.'}',$value, $message_template['text']);
                        $message_template['text'] = str_replace('{payment_method}',$cart_data['paymentInfo']['name'], $message_template['text']);
                        $message_template['text'] = str_replace('{delivery_method}',$cart_data['deliveryInfo']['name'], $message_template['text']);
                        $message_template['text'] = str_replace('{products}',$this->load->view('email/order', ['products' => $products,'suppliers' => $this->supplier_model->suppliers], true), $message_template['text']);
                        $message_template['text_sms'] = str_replace('{'.$field.'}',$value, $message_template['text_sms']);
                    }
                    $this->sender->email($message_template['subject'], $message_template['text'], explode(';',$this->contacts['email']),explode(';',$this->contacts['email']));



                    if(!empty($save['telephone'])){
                        $this->sender->sms($save['telephone'], $message_template['text_sms']);
                    }

                    $this->cart->destroy();

                    //Возвразаем отложенные товары в корзину
                    if(isset($_SESSION['deferred'])){
                        foreach ($_SESSION['deferred'] as $deferred){
                            $this->cart->insert($deferred);
                        }
                        unset($_SESSION['deferred']);
                    }
                    
                    //Если это api платежной системы передаем ей управление
                    if(!empty($cart_data['paymentInfo']['api'])){
                        redirect('/payment/'.$cart_data['paymentInfo']['api'].'?amount='.$cart_data['total']);
                    }
                    
                    $this->session->set_flashdata('success', sprintf(lang('text_success_order'), $order_id));

                    redirect('/customer');
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
        $deliveryInfo = false;
        if($delivery_id){
            $this->load->model('delivery_model');
            $deliveryInfo = $this->delivery_model->delivery_get($delivery_id);

            if($deliveryInfo['api'] && file_exists(APPPATH.'libraries/delivery/'.ucfirst($deliveryInfo['api'].'.php'))){
                $this->load->library('delivery/'.$deliveryInfo['api']);
                $json['delivery_description'] = $this->{$deliveryInfo['api']}->get_form();
                $delivery_price = $this->{$deliveryInfo['api']}->delivery_price();
                $this->load->remove_package_path();
            }else{
                if($deliveryInfo['free_cost'] == 0  || $total < $deliveryInfo['free_cost']){
                    $delivery_price = (float)$deliveryInfo['price'];
                }
                $json['delivery_description'] = $deliveryInfo['description'];
            }
            //Связка способов доставки с способами оплаты
            $json['link_payments'] = $deliveryInfo['payment_methods'];
        }

        $json['payment_description'] = '';
        $payment_id = (int)$this->input->post('payment_method', true);
        $paymentInfo = false;
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



        $json['delivery_price'] = $delivery_price;
        $json['commissionpay'] = $commissionpay;
        $json['subtotal'] = $this->cart->total();
        $json['total_val'] = $total + $delivery_price + $commissionpay;
        $json['total'] = $json['total_val'];
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
        if(!$quantity){
            $quantity = 1;
        }

        $json = $this->cart_model->addCart($product_id,$supplier_id,$term,$quantity);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    private function auto_registration(){
        $this->load->model('message_template_model');
        $this->load->model('customer_model');
        $pass = rand(1000,9999);
        $save = [];
        $save['first_name'] = $this->input->post('first_name', true);
        $save['second_name'] = $this->input->post('last_name', true);
        $save['patronymic'] = $this->input->post('patronymic', true);
        $save['email'] = $this->input->post('email', true);
        $save['address'] = $this->input->post('address', true);
        $save['customer_group_id'] = (int)$this->customergroup_model->get_default();
        $save['password'] = password_hash($pass, PASSWORD_BCRYPT);
        $save['phone'] = format_phone($this->input->post('telephone', true));
        $save['created_at'] = date('Y-m-d H:i:s');
        $save['status'] = $this->config->item('active_new_customer');
        $save['negative_balance'] = (int)$this->config->item('negative_balance');
        $save['payment_method_id'] = (int)$this->input->post('payment_method_id');
        $save['delivery_method_id'] = (int)$this->input->post('delivery_method_id');

        $customer_id = $this->customer_model->insert($save,false);

        if($customer_id){
            //Получаем шаблон сообщения 3 - Регистрация
            $message_template = $this->message_template_model->get(3);

            foreach ($save as $field => $value) {
                $message_template['subject'] = str_replace('{' . $field . '}', $value, $message_template['subject']);
                $message_template['text'] = str_replace('{' . $field . '}', $value, $message_template['text']);
                $message_template['text_sms'] = str_replace('{' . $field . '}', $value, $message_template['text_sms']);
            }
            $message_template['subject'] = str_replace('{pass}', $pass, $message_template['subject']);
            $message_template['text'] = str_replace('{pass}',$pass, $message_template['text']);
            $message_template['text_sms'] = str_replace('{pass}',$pass, $message_template['text_sms']);
            $message_template['subject'] = str_replace('{customer_id}', $customer_id, $message_template['subject']);
            $message_template['text'] = str_replace('{customer_id}', $customer_id, $message_template['text']);
            $message_template['text_sms'] = str_replace('{customer_id}', $customer_id, $message_template['text_sms']);

            $this->sender->email($message_template['subject'], $message_template['text'], explode(';', $this->contacts['email']), explode(';', $this->contacts['email']));

            if ($save['email'] && $message_template['text']) {
                $this->sender->email($message_template['subject'], $message_template['text'], $save['email'], explode(';', $this->contacts['email']));
            }

            if ($save['phone'] && $message_template['text_sms']) {
                $this->sender->sms($save['phone'], $message_template['text_sms']);
            }
        }

        $this->customer_model->login($save['phone'], $pass);
        return $customer_id;

    }
}