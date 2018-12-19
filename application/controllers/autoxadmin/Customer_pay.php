<?php

/**
 * Created by PhpStorm.
 * User: serg
 * Date: 05.06.2017
 * Time: 13:10
 */

class Customer_pay extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_pay_model');
        $this->load->model('customerbalance_model');
        $this->load->language('admin/customer_pay');
    }

    public function index(){
        $data['pays'] = array();
        $this->load->library('pagination');


        $config['base_url'] = base_url('autoxadmin/customer_pay/index');
        $config['per_page'] = 30;
        $pays = $this->customer_pay_model->customer_pay_get_all($config['per_page'], $this->uri->segment(4));
        if($pays){
            foreach ($pays as $pay){
                $data['pays'][format_date($pay['transaction_date'])][] = $pay;
            }
        }
        $config['total_rows'] = $this->customer_pay_model->total_rows;
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);

        $data['statuses'] = $this->customer_pay_model->statuses;

        $this->load->view('admin/header');
        $this->load->view('admin/customer_pay/index', $data);
        $this->load->view('admin/footer');
    }

    public function edit($id){
       if($this->input->post()){
          $this->_save_data($id);
       }

       $this->_get_form($id);
    }

    public function create(){
        if($this->input->post()){
            $this->_save_data();
        }

        $this->_get_form();
    }

    private function _get_form($id = false){
        $data['statuses'] = $this->customer_pay_model->statuses;
        $data['id'] = $id;

        if($id){
            $data['action'] = '/autoxadmin/customer_pay/edit/'.$id;
            $pay_info = $this->customer_pay_model->get($id);
            //Если оплата уже подверждена, запречаем редактирование
            if($pay_info['status_id'] == 1){
                $this->session->set_flashdata('error', 'Оплата подтверждена, редактирование запрещено.');
                redirect('/autoxadmin/customer_pay');
            }
        }else{
            $data['action'] = '/autoxadmin/customer_pay/create';
            $pay_info = false;
        }

        if($this->input->post('customer_id')){
            $data['customer_id'] = $this->input->post('customer_id', true);
        }else if($pay_info){
            $data['customer_id'] = $pay_info['customer_id'];
        }else{
            $data['customer_id'] = '';
        }

        if($this->input->post('amount')){
            $data['amount'] = $this->input->post('amount', true);
        }else if($pay_info){
            $data['amount'] = $pay_info['amount'];
        }else{
            $data['amount'] = '';
        }

        if($this->input->post('date')){
            $data['date'] = $this->input->post('date', true);
        }else if($pay_info){
            $data['date'] = date('Y-m-d', strtotime($pay_info['transaction_date']));
        }else{
            $data['date'] = '';
        }

        if($this->input->post('time')){
            $data['time'] = $this->input->post('time', true);
        }else if($pay_info){
            $data['time'] = date('H:i:s', strtotime($pay_info['transaction_date']));
        }else{
            $data['time'] = '';
        }

        if($this->input->post('comment')){
            $data['comment'] = $this->input->post('comment', true);
        }else if($pay_info){
            $data['comment'] = $pay_info['comment'];
        }else{
            $data['comment'] = '';
        }

        if($this->input->post('status_id')){
            $data['status_id'] = $this->input->post('status_id', true);
        }else if($pay_info){
            $data['status_id'] = $pay_info['status_id'];
        }else{
            $data['status_id'] = '';
        }


        $this->load->view('admin/header');
        $this->load->view('admin/customer_pay/form', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        //Отменяем транзакцию
        $balance_info = $this->customerbalance_model->getByPay($id);
        if($balance_info){
            $this->customerbalance_model->delete($balance_info['id']);
            $this->session->set_flashdata('success', 'Транзакция отменена. Баланс пересчитан');
        }

        $this->customer_pay_model->delete($id);

        redirect('/autoxadmin/customer_pay');
    }

    protected function _save_data($id = false){
        $customerInfo = $this->customer_model->get($this->input->post('customer_id', true));

        if(!$customerInfo){
            $this->session->set_flashdata('error', 'Клиент с ID '.$this->input->post('customer_info', true).' не найден');
            if($id){
                redirect('/autoxadmin/customer_pay/edit/'.$id);
            }else{
                redirect('/autoxadmin/customer_pay');
            }
        }

        $save['customer_id'] = $customerInfo['id'];
        $save['status_id'] = (int)$this->input->post('status_id');
        $save['amount'] = (float)$this->input->post('amount');
        $save['transaction_date'] = date('Y-m-d H:i:s',strtotime($this->input->post('date', true).' '.$this->input->post('time', true)));
        $save['comment'] = $this->input->post('comment', true);

        if($id = $this->customer_pay_model->insert($save,$id)){
            //Если статус оплаты подтвержен добавляем транзакцию
            if($save['status_id'] == 1){
                $this->customerbalance_model->add_transaction(
                    $save['customer_id'],
                    (float)$save['amount'],
                    lang('text_customer_pay').' '. (int)$id,
                    1, //Зачисдение
                    $save['transaction_date'],
                    $this->session->userdata('user_id'),
                    0,
                    $id
                );
            }
        }
        $this->session->set_flashdata('success', lang('text_success'));

        redirect('/autoxadmin/customer_pay');
    }
}