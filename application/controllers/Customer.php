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

    public function pay($order_id)
    {
        $orderInfo = $this->order_model->get($order_id);
        if (!$orderInfo || $orderInfo['customer_id'] != $this->session->userdata('customer_id')) {
            $this->session->set_flashdata('error', 'Заказ не найтед');
            redirect('/customer');
        }

        if ($this->customer_balance < $orderInfo['total']) {
            $this->session->set_flashdata('error', 'Не достаточно средств для оплаты.');
            redirect('/customer');
        }

        $save['customer_id'] = $this->session->userdata('customer_id');
        $save['type'] = 2;
        $save['value'] = (float)$orderInfo['total'];
        $save['transaction_created_at'] = date("Y-m-d H:i:s");
        $save['description'] = 'Оплата заказа №' . $orderInfo['id'];
        $save['created_at'] = date("Y-m-d H:i:s");
        $save['user_id'] = 0;
        if ($this->customerbalance_model->insert($save)) {
            //Обновляем баланс покупателя
            if ($save['type'] == 1) {
                $save2['balance'] = $this->customer_balance + $save['value'];
            } else {
                $save2['balance'] = $this->customer_balance - $save['value'];
            }
            $this->customer_model->insert($save2, $save['customer_id']);

            //Ставим ОПЛАЧЕН заказу
            $save3['paid'] = 1;
            $this->order_model->insert($save3, $orderInfo['id']);

            //Комментарий к заказу
            $this->load->model('order_history_model');
            $history['order_id'] = $order_id;
            $history['date'] = date("Y-m-d H:i:s");
            $history['text'] = 'Оплата заказа c баланса. Сумма '.$orderInfo['total'];
            $history['user_id'] = 0;
            $this->order_history_model->insert($history);

        }
        $this->session->set_flashdata('success', 'Заказ успешно оплачен');

        redirect('/customer');
    }

    public function index()
    {
        $this->customer_model->is_login('/customer/login');
        $data = [];
        $config['base_url'] = base_url('customer/index');
        $config['total_rows'] = $this->order_model->count_all(['customer_id' => $this->is_login]);
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $data['orders'] = $this->order_model->get_all($config['per_page'], $this->uri->segment(3), ['customer_id' => $this->is_login]);
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
            $this->form_validation->set_rules('login', lang('text_login'), 'required|max_length[32]|trim|is_unique[customer.login]');
            $this->form_validation->set_rules('phone', lang('text_phone'), 'required|max_length[32]|trim');

            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'trim|required');
            $this->form_validation->set_rules('second_name', lang('text_second_name'), 'trim|required');
            $this->form_validation->set_rules('patronymic', lang('patronymic'), 'trim|required');
            $this->form_validation->set_rules('email', lang('text_email'), 'required|valid_email');
            $this->form_validation->set_rules('captcha', 'Проверочный код', 'trim|required|callback_validate_captcha');

            $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
            $this->form_validation->set_rules('confirm_password', lang('text_confirm_password'), 'required|trim|matches[password]');

            if ($this->form_validation->run() !== false) {
                $customer_id = $this->save_data();

                //Получаем шаблон сообщения 3 - Регистрация
                $message_template = $this->message_template_model->get(3);
                foreach ($this->input->post() as $field => $value) {
                    $message_template['subject'] = str_replace('{' . $field . '}', $value, $message_template['subject']);
                    $message_template['text'] = str_replace('{' . $field . '}', $value, $message_template['text']);
                    $message_template['text_sms'] = str_replace('{' . $field . '}', $value, $message_template['text_sms']);
                }

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

                if ($this->customer_model->login($this->input->post('login', true), $this->input->post('password', true))) {
                    $this->session->set_flashdata('success', sprintf(lang('text_success_login'), $this->session->customer_name));
                    redirect('/');
                } else {
                    $this->session->set_flashdata('error', 'ERROR REGISTRATION');
                    redirect('/');
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
        $this->session->sess_destroy();
        redirect('/');
    }

    public function orderinfo($id = false)
    {

        $data['order_info'] = $this->order_model->order_get($id);
        if (!$data['order_info']) {
            show_404();
        }

        $data['order_products'] = $this->order_product_model->product_get($data['order_info']['id']);

        $this->load->view('customer/orderinfo', $data);
    }

    public function forgot()
    {
        $data = [];
        if ($this->input->post()) {
            $this->form_validation->set_rules('email', lang('text_email'), 'trim|required|valid_email');
            if ($this->form_validation->run() !== false) {
                $this->load->helper('string');
                $user = $this->customer_model->getByEmail($this->input->post('email', true));
                if ($user) {
                    $new_password = random_string();

                    $save['password'] = password_hash($new_password, PASSWORD_BCRYPT);
                    $this->customer_model->insert($save, $user['id']);

                    $this->load->library('sender');

                    $send_email = '';
                    if ($user['email']) {
                        $this->sender->email('News password', 'New password: ' . $new_password, $user['email'], $this->contacts['email']);
                        $send_email = 'в email';
                    }

                    $send_sms = '';
                    if ($user['phone']) {
                        $this->sender->sms($user['phone'], $new_password);
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
        $save['login'] = $this->input->post('login', true);
        $save['password'] = password_hash($this->input->post('password', true), PASSWORD_BCRYPT);
        $save['phone'] = $this->input->post('phone', true);
        $save['created_at'] = date('Y-m-d H:i:s');
        $save['status'] = $this->config->item('active_new_customer');

        //Удаляем картинки каптчи
        $this->load->helper('file');
        delete_files('./captcha', true);

        return $this->customer_model->insert($save, $id);
    }

    public function profile(){
        $data['customer'] = $this->customer_model->get($this->is_login);
        $data['customer_group'] = $this->customergroup_model->get($data['customer']['customer_group_id']);

        if ($this->input->post()) {
            if ($this->input->post('login') != $data['customer']['login']) {
                $this->form_validation->set_rules('login', lang('text_login'), 'required|max_length[32]|trim|is_unique[customer.login]');
            }
            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'max_length[250]|trim');
            $this->form_validation->set_rules('second_name', lang('text_second_name'), 'max_length[250]|trim');
            $this->form_validation->set_rules('patronymic', lang('patronymic'), 'max_length[255]|trim|required');
            $this->form_validation->set_rules('address', lang('text_address'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('email', lang('text_email'), 'valid_email|trim');
            $this->form_validation->set_rules('phone', lang('text_phone'), 'trim|required');
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
                $this->form_validation->set_rules('confirm_password', lang('text_confirm_password'), 'required|trim|matches[password]');
            }

            if ($this->form_validation->run() !== false) {
                $save = [];
                $save['login'] = $this->input->post('login', true);
                $save['customer_group_id'] = (int)$data['customer']['customer_group_id'];
                $save['first_name'] = $this->input->post('first_name', true);
                $save['second_name'] = $this->input->post('second_name', true);
                $save['patronymic'] = $this->input->post('patronymic', true);
                $save['address'] = $this->input->post('address', true);
                $save['email'] = $this->input->post('email', true);
                $save['phone'] = $this->input->post('phone', true);
                if ($this->input->post('password')) {
                    $save['password'] = password_hash($this->input->post('password', true), PASSWORD_BCRYPT);
                }

                $save['updated_at'] = date("Y-m-d H:i:s");

                $save['status'] = true;
                $id = $this->customer_model->insert($save, $data['customer']['id']);
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
        $data['recharge'] = $this->settings_model->get_by_key('recharge');

        if($this->input->post()){
            $this->load->library('sender');
            $this->load->model('customer_model');
            $customer_info = $this->customer_model->get($this->is_login);
            if($customer_info){
                $subject = 'Сообщение об оплате';
                $text = 'Логин:'.$customer_info['login'].'<br>Сумма:'.$this->input->post('sum',true).'<br>Комментарий:'.$this->input->post('comment',true);
                $this->sender->email($subject,$text,$this->contacts['email'],$this->contacts['email']);
                $this->session->set_flashdata('success', 'Сообщение отправлено');
                redirect('customer/balance');
            }
        }
        $this->load->model('customerbalance_model');
        $this->customer_model->is_login('/customer/login');

        $config['base_url'] = base_url('customer/balance');
        $config['total_rows'] = $this->customerbalance_model->count_all(['customer_id' => $this->is_login]);
        $config['per_page'] = 50;

        $this->pagination->initialize($config);

        $data['balances'] = $this->customerbalance_model->get_all($config['per_page'], $this->uri->segment(3), ['customer_id' => $this->is_login],['id' => 'desc']);
        $data['types'] = $this->customerbalance_model->types;
        $this->load->view('header');
        $this->load->view('customer/balance', $data);
        $this->load->view('footer');
    }

    public function products(){
        $this->load->model('orderstatus_model');
        $this->load->model('order_product_model');
        $data = [];
        $data['statuses'] = $this->orderstatus_model->status_get_all();

        $config['base_url'] = base_url('customer/products');
        $config['per_page'] = 50;
        $data['products'] = $this->order_product_model->get_products_by_customer($this->is_login,$config['per_page'], $this->uri->segment(3));
        $config['total_rows'] = $this->order_product_model->total_rows;
        $config['reuse_query_string'] = true;


        $this->pagination->initialize($config);


        $this->load->view('header');
        $this->load->view('customer/products', $data);
        $this->load->view('footer');
    }
}