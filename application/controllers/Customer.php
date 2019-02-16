<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Front_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('customer');
        $this->load->model('customergroup_model');
        $this->load->model('order_model');
        $this->load->model('orderstatus_model');
        $this->load->model('order_product_model');
        $this->load->library('pagination');
        $this->load->model('message_template_model');
        $this->load->model('customerbalance_model');
        $this->load->library('sender');
    }

    public function index()
    {
        $this->customer_model->is_login('/customer/login');
        $data = [];
        $config['base_url'] = base_url('customer/index');
        $config['total_rows'] = $this->order_model->count_all(['customer_id' => $this->is_login]);
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $data['orders'] = $this->order_model->get_all($config['per_page'], $this->uri->segment(3), ['customer_id' => $this->is_login],['id' => 'DESC']);
        if($data['orders']){
            foreach ($data['orders'] as &$order){
                $order['products'] = $this->order_product_model->product_get($order['id']);
            }
        }

        $data['status'] = $this->orderstatus_model->status_get_all();


        $this->load->view('header');
        $this->load->view('customer/customer', $data);
        $this->load->view('footer');
    }

    public function registration()
    {
        if ($this->is_login) {
            redirect('/customer');
        }

        if ($this->input->post()) {

            if($this->input->post('phone')){
                $_POST['phone'] = format_phone($_POST['phone']);
            }

            $this->form_validation->set_rules('phone', lang('text_phone'), 'required|min_length[10]|max_length[32]|trim|is_unique[customer.phone]');

            $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
            $this->form_validation->set_rules('confirm_password', lang('text_confirm_password'), 'required|trim|matches[password]');

            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'trim|required');
            $this->form_validation->set_rules('second_name', lang('text_second_name'), 'trim|required');
            $this->form_validation->set_rules('patronymic', lang('patronymic'), 'trim|required');
            $this->form_validation->set_rules('email', lang('text_email'), 'required|valid_email|is_unique[customer.email]');
            $this->form_validation->set_rules('captcha', lang('text_captcha'), 'trim|required|callback_validate_captcha');


            if ($this->form_validation->run() !== false) {
                $customer_id = $this->save_data();
                if($customer_id){
                    //Получаем шаблон сообщения 3 - Регистрация
                    $message_template = $this->message_template_model->get(3);
                    foreach ($this->input->post() as $field => $value) {
                        $message_template['subject'] = str_replace('{' . $field . '}', $value, $message_template['subject']);
                        $message_template['text'] = str_replace('{' . $field . '}', $value, $message_template['text']);
                        $message_template['text_sms'] = str_replace('{' . $field . '}', $value, $message_template['text_sms']);
                    }

                    $message_template['subject'] = str_replace('{pass}', $this->input->post('password', true), $message_template['subject']);
                    $message_template['text'] = str_replace('{pass}',$this->input->post('password', true), $message_template['text']);
                    $message_template['text_sms'] = str_replace('{pass}',$this->input->post('password', true), $message_template['text_sms']);

                    $message_template['subject'] = str_replace('{customer_id}', $customer_id, $message_template['subject']);
                    $message_template['text'] = str_replace('{customer_id}', $customer_id, $message_template['text']);
                    $message_template['text_sms'] = str_replace('{customer_id}', $customer_id, $message_template['text_sms']);

                    $this->sender->email($message_template['subject'], $message_template['text'], explode(';', $this->contacts['email']), explode(';', $this->contacts['email']));

                    if ($this->input->post('email')) {
                        $this->sender->email($message_template['subject'], $message_template['text'], $this->input->post('email'), explode(';', $this->contacts['email']));
                    }

                    if ($this->input->post('phone')) {
                        $this->sender->sms($this->input->post('phone'), $message_template['text_sms']);
                    }

                    if ($this->customer_model->login($this->input->post('phone'), $this->input->post('password', true))) {
                        $this->session->set_flashdata('success', sprintf(lang('text_success_login'), $this->session->customer_name));
                        redirect('/');
                    } else {
                        $this->session->set_flashdata('error', 'ERROR REGISTRATION');
                        redirect('/');
                    }
                }

            } else {
                $this->error = validation_errors();
            }
        }

        $this->load->helper('captcha');
        $vals = array(
            'word' => rand(10000, 99999),
            'img_path' => './captcha/',
            'img_url' => base_url('captcha'),
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 255, 255)
            )
        );

        $captcha = create_captcha($vals);
        $this->session->set_userdata('captcha', $captcha);
        $data['captcha_image'] = $captcha['image'];

        $this->load->view('header');
        $this->load->view('customer/registration', $data);
        $this->load->view('footer');
    }

    public function validate_captcha()
    {
        if ($this->input->post('captcha') != $this->session->userdata['captcha']['word']) {
            $this->form_validation->set_message('validate_captcha', 'Wrong captcha code, hmm are you the Terminator?');
            return false;
        } else {
            return true;
        }

    }

    public function logout()
    {
        if($this->is_admin){
            unset($_SESSION['customer_id']);
            unset($_SESSION['customer_group_id']);
            unset($_SESSION['customer_name']);
            unset($_SESSION['cart_contents']);
        }else{
            $this->session->sess_destroy(session_id());
        }


        redirect('/');
    }

    public function orderinfo($id = false)
    {
        $data['order_info'] = $this->order_model->order_get($id);
        if (!$data['order_info']) {
            show_404();
        }

        if($data['order_info']['customer_id'] == 0 && !$this->is_admin){
            show_404();
        }

        $data['order_products'] = $this->order_product_model->product_get($data['order_info']['id']);

        $this->load->view('customer/orderinfo', $data);
    }

    public function forgot()
    {
        $data = [];
        if ($this->input->post()) {
            $this->form_validation->set_rules('search', lang('text_forgot_input'), 'trim|required');
            if ($this->form_validation->run() !== false) {

                $this->load->helper('string');

                $customer = $this->customer_model->getByEmail($this->input->post('search', true));
                if(!$customer){
                    $customer = $this->customer_model->getByPhone($this->input->post('search', true));
                }

                if ($customer) {
                    $new_password = random_string();

                    $save['password'] = password_hash($new_password, PASSWORD_BCRYPT);
                    $this->customer_model->insert($save, $customer['id']);

                    $this->load->library('sender');

                    $send_email = '';
                    if ($customer['email']) {
                        $this->sender->email('News password', 'New password: ' . $new_password, $customer['email'], $this->contacts['email']);
                        $send_email = 'в email';
                    }

                    $send_sms = '';
                    if ($customer['phone']) {
                        $this->sender->sms($customer['phone'], $new_password);
                        $send_sms = 'в SMS';
                    }

                    $this->session->set_flashdata('success', lang('text_forgot_success') . ' ' . $send_email . ' ' . $send_sms);
                    redirect('/');
                } else {
                    $this->error = lang('error_by_email');
                }

            } else {
                $this->error = validation_errors();
            }
        }
        $this->load->view('header');
        $this->load->view('customer/forgot', $data);
        $this->load->view('footer');
    }

    private function save_data($id = false)
    {
        $save = [];
        $save['first_name'] = $this->input->post('first_name', true);
        $save['second_name'] = $this->input->post('second_name', true);
        $save['patronymic'] = $this->input->post('patronymic', true);
        $save['email'] = $this->input->post('email', true);
        $save['address'] = $this->input->post('address', true);
        $save['customer_group_id'] = (int)$this->customergroup_model->get_default();
        $save['password'] = password_hash($this->input->post('password', true), PASSWORD_BCRYPT);
        $save['phone'] = format_phone($this->input->post('phone', true));
        $save['created_at'] = date('Y-m-d H:i:s');
        $save['status'] = $this->config->item('active_new_customer');
        $save['negative_balance'] = (int)$this->config->item('negative_balance');


        //Удаляем картинки каптчи
        $this->load->helper('file');
        delete_files('./captcha', true);

        return $this->customer_model->insert($save, $id);
    }

    public function profile(){
        $this->customer_model->is_login('/customer/login');
        $data['customer'] = $this->customer_model->get($this->is_login);
        $data['customer_group'] = $this->customergroup_model->get($data['customer']['customer_group_id']);

        if ($this->input->post()) {
            $_POST['phone'] = format_phone($_POST['phone']);

            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'max_length[250]|trim');
            $this->form_validation->set_rules('second_name', lang('text_second_name'), 'max_length[250]|trim');
            $this->form_validation->set_rules('patronymic', lang('patronymic'), 'max_length[255]|trim|required');
            $this->form_validation->set_rules('address', lang('text_address'), 'max_length[3000]|trim');

            if($this->input->post('email',true) != $data['customer']['email']){
                $this->form_validation->set_rules('email', lang('text_email'), 'required|valid_email|trim|is_unique[customer.email]');
            }else{
                $this->form_validation->set_rules('email', lang('text_email'), 'required|valid_email|trim');
            }
            if($this->input->post('phone',true) != $data['customer']['phone']){
                $this->form_validation->set_rules('phone', lang('text_phone'), 'trim|required|is_unique[customer.phone]');
            }else{
                $this->form_validation->set_rules('phone', lang('text_phone'), 'trim|required');
            }
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
                $this->form_validation->set_rules('confirm_password', lang('text_confirm_password'), 'required|trim|matches[password]');
            }

            if ($this->form_validation->run() !== false) {
                $id = $this->save_data($data['customer']['id']);

                if ($id) {
                    $this->session->set_flashdata('success', lang('text_success'));
                    redirect('customer/profile');
                }
            } else {
                $this->error = validation_errors();
            }
        }

        $this->load->view('header');
        $this->load->view('customer/profile', $data);
        $this->load->view('footer');
    }

    public function balance(){
        $this->customer_model->is_login('/customer/login');
        $data['recharge'] = $this->settings_model->get_by_key('recharge');

        if($this->input->get('csv')){
            $this->load->dbutil();
            $sql = "SELECT * FROM ax_customer_balance WHERE customer_id = '".(int)$this->is_login."'";

            if($this->input->get('date_from')){
                $sql .= " AND DATE(created_at) >= ".$this->db->escape($this->input->get('date_from', true));
            }

            if($this->input->get('date_to')){
                $sql .= " AND DATE(created_at) <= ".$this->db->escape($this->input->get('date_to', true));
            }

            if($this->input->get('type') && $this->input->get('type') != '*'){
                $sql .= " AND type = ".(int)$this->input->get('type');
            }

            $query = $this->db->query($sql);

            $config = array (
                'root'          => 'root',
                'element'       => 'element',
                'newline'       => "\n",
                'tab'           => "\t"
            );
            header("Content-disposition: attachment; filename=balance.csv");
            header("Content-type: application/vnd.ms-excel");
            $delimiter = ";";
            $newline = "\r\n";
            $enclosure = '"';

            echo $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
            die();
        }

        if($this->input->post()){
            $this->load->library('sender');
            $this->load->model('customer_model');
            $customer_info = $this->customer_model->get($this->is_login);
            if($customer_info){
                $this->form_validation->set_rules('sum', 'Сумма', 'required|numeric');
                $this->form_validation->set_rules('date', 'Дата и время', 'required');
                $this->form_validation->set_rules('time', 'Дата и время', 'required');

                if ($this->form_validation->run() !== false){
                    $this->load->model('customer_pay_model');
                    $save['customer_id'] = (int)$customer_info['id'];
                    $save['amount'] = (float)$this->input->post('sum',true);
                    $save['transaction_date'] = date("Y-m-d H:i:s",strtotime($this->input->post('date', true).' '.$this->input->post('time', true)));
                    $save['comment'] = (string)$this->input->post('comment', true);

                    $this->customer_pay_model->insert($save);

                    $subject = 'Сообщение об оплате';
                    $text = 'Клиент ID :'.$customer_info['id'].'<br>Сумма:'.$this->input->post('sum',true).'<br>Комментарий:'.$this->input->post('comment',true);
                    $this->sender->email($subject,$text,$this->contacts['email'],$this->contacts['email']);
                    $this->session->set_flashdata('success', 'Сообщение отправлено');
                    redirect('customer/balance');
                }else{
                    $this->error = validation_errors();
                }


            }
        }
        $this->load->model('customerbalance_model');
        $this->customer_model->is_login('/customer/login');

        $where['customer_id'] = $this->is_login;
        if($this->input->get('date_from')){
            $where['DATE(created_at)'] = $this->input->get('date_from', true);
        }

        if($this->input->get('date_to')){
            $where['DATE(created_at)'] = $this->input->get('date_to', true);
        }

        if($this->input->get('type') && $this->input->get('type') != '*'){
            $where['type'] = (int)$this->input->get('type');
        }

        $config['base_url'] = base_url('customer/balance');
        $config['total_rows'] = $this->customerbalance_model->count_all($where);
        $config['per_page'] = 20;
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        $data['balances'] = $this->customerbalance_model->get_all($config['per_page'], $this->uri->segment(3), $where,['id' => 'desc']);
        $data['types'] = $this->customerbalance_model->types;
        $this->load->view('header');
        $this->load->view('customer/balance', $data);
        $this->load->view('footer');
    }

    public function products(){
        $this->customer_model->is_login('/customer/login');

        $this->load->model('orderstatus_model');
        $this->load->model('order_product_model');

        $data = [];

        $data['statuses'] = $this->orderstatus_model->status_get_all();

        $config['base_url'] = base_url('customer/products');
        $config['per_page'] = 50;
        $data['products'] = $this->order_product_model->get_products_by_customer($this->is_login,$config['per_page'], $this->uri->segment(3));
        $config['total_rows'] = $this->order_product_model->total_rows;
        $config['reuse_query_string'] = true;

        $data['status_totals'] = $this->order_product_model->get_status_totals($data['statuses'], $this->is_login);

        $this->pagination->initialize($config);


        $this->load->view('header');
        $this->load->view('customer/products', $data);
        $this->load->view('footer');
    }

    public function search_history(){
        $this->customer_model->is_login('/customer/login');
        $this->load->model('search_history_model');

        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('/customer/search_history');
        $config['per_page'] = 30;
        $data['search_history'] = $this->search_history_model->search_history_customer($config['per_page'], $this->uri->segment(3),$this->is_login);
        $config['total_rows'] = $this->search_history_model->total_rows;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $this->load->view('header');
        $this->load->view('customer/search_history', $data);
        $this->load->view('footer');
    }

    public function vin(){
        $this->customer_model->is_login('/customer/login');
        $this->load->model('vinrequest_model');

        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('/customer/vin');
        $config['per_page'] = 30;
        $data['vins'] = $this->vinrequest_model->get_all($config['per_page'], $this->uri->segment(3),['customer_id' => $this->is_login],['id' => 'DESC']);
        $config['total_rows'] = $this->vinrequest_model->count_all(['customer_id' => $this->is_login]);
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $this->load->view('header');
        $this->load->view('customer/vin', $data);
        $this->load->view('footer');
    }

    public function vin_info($id = false){
        $this->customer_model->is_login('/customer/login');
        $this->load->model('vinrequest_model');
        $vin_info = $this->vinrequest_model->get($id);
        if(!$id || !$vin_info || $vin_info['customer_id'] != $this->is_login){
            $this->output->set_status_header(410, lang('text_page_404'));
            $this->load->view('header');
            $this->load->view('page_404');
            $this->load->view('footer');
            return;
        }

        $data['vin_info'] = $vin_info;

        $this->load->view('header');
        $this->load->view('customer/vin_info', $data);
        $this->load->view('footer');
    }

    public function login()
    {

        $json = [];
        $this->load->language('customer');
        $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
        if($this->input->post()){
            if ($this->form_validation->run() !== false) {

                if($this->input->post('phone')){
                    $login = $this->input->post('phone', true);
                }

                if($this->input->post('email')){
                    $login = $this->input->post('email', true);
                }

                $password = $this->input->post('password', true);
                if ($this->customer_model->login($login, $password)) {
                    $this->session->set_flashdata('success', sprintf(lang('text_success_login'), $this->session->customer_name));
                    $json['success'] = true;
                } else {
                    $json['error'] = lang('text_error');
                }
            } else {
                $json['error'] = validation_errors();
            }

            if($this->input->is_ajax_request()){
                exit(json_encode($json));
            }else{
                redirect('/customer/login');
            }
        }

        $this->load->view('header');
        $this->load->view('customer/login');
        $this->load->view('footer');
    }

    public function invoices(){
        $this->customer_model->is_login('/customer/login');

        $this->load->model('invoice_model');

        $data = [];
        $config['base_url'] = base_url('customer/invoices');
        $config['total_rows'] = $this->invoice_model->count_all(['customer_id' => $this->is_login]);
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $data['invoices'] = $this->invoice_model->get_all($config['per_page'], $this->uri->segment(3), ['customer_id' => $this->is_login],['id' => 'DESC']);
        if($data['invoices']){
            foreach ($data['invoices'] as &$invoice){
                $invoice['total'] = $this->invoice_model->getTotal($invoice['id']);
            }
        }

        $data['statuses'] = $this->invoice_model->statuses;

        $this->load->view('header');
        $this->load->view('customer/invoices', $data);
        $this->load->view('footer');
    }

    public function invoice($id){
        $this->load->model('invoice_model');

        $data['invoice_info'] = $this->invoice_model->get($id);

        if($data['invoice_info']['customer_id'] != $this->customer_model->id){
            show_404();
        }
        $data['invoice_products'] = $this->invoice_model->getProducts($id);
        $data['total'] = $this->invoice_model->getTotal($id);
        $data['customer_info'] = $this->customer_model->get($data['invoice_info']['customer_id']);

        $this->load->view('customer/invoice_view', $data);
    }

    public function pay($id){
        $this->load->model('customer_pay_model');
        $data['pay_info'] = $this->customer_pay_model->get($id);
        print_r($data['pay_info']);
        exit($id);
    }
}