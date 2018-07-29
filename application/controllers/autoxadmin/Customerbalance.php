<?php

/**
 * Created by PhpStorm.
 * User: serg
 * Date: 05.06.2017
 * Time: 13:10
 */

class Customerbalance extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customerbalance_model');
        $this->load->model('customer_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/customerbalance/index');
        $config['per_page'] = 30;
        $data['balances'] = $this->customerbalance_model->customerbalance_get_all($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->customerbalance_model->total_rows;
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);

        $data['types'] = $this->customerbalance_model->types;

        $this->load->view('admin/header');
        $this->load->view('admin/customerbalance/customerbalance', $data);
        $this->load->view('admin/footer');
    }

    public function id_check($str)
    {
        if (!$this->customer_model->get($str))
        {
            $this->form_validation->set_message('id_check', 'Customer '.$str.' not find');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function create(){
        $data['types'] = $this->customerbalance_model->types;
        if($this->input->post()){
            $this->form_validation->set_rules('id', 'ID клиента', 'required|callback_id_check|trim');
            $this->form_validation->set_rules('type', 'Тип транзакции', 'required|integer');
            $this->form_validation->set_rules('value', 'Сумма', 'required|numeric');
            $this->form_validation->set_rules('transaction_created_at', 'Дата транзакции', 'required');
            $this->form_validation->set_rules('description', 'Описание транзакции', 'required');
            if ($this->form_validation->run() !== false){
                $this->_save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/customerbalance/create', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->customerbalance_model->delete($id);
        $this->session->set_flashdata('success', 'Транзакция удалена, баланс клиента не пересчитан.');

        redirect('/autoxadmin/customerbalance');
    }

    protected function _save_data($id = false){
        $customerInfo = $this->customer_model->get($this->input->post('id', true));
        $save['customer_id'] = $this->input->post('id', true);
        $save['type'] = (int)$this->input->post('type');
        $save['value'] = (float)$this->input->post('value');
        $save['transaction_created_at'] = $this->input->post('transaction_created_at', true);
        $save['description'] = $this->input->post('description', true);
        $save['created_at'] = date("Y-m-d H:i:s");
        $save['user_id'] = $this->session->userdata('user_id');
        if($this->customerbalance_model->insert($save,$id)){
            //Обновляем баланс покупателя
            if($save['type'] == 1){
                $save2['balance'] = $customerInfo['balance'] + $save['value'];
            }else{
                $save2['balance'] = $customerInfo['balance'] - $save['value'];
            }
            $this->customer_model->insert($save2,$customerInfo['id']);
        }
        $this->session->set_flashdata('success', lang('text_success'));

        redirect('/autoxadmin/customerbalance');
    }
}