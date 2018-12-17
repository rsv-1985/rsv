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

    public function index()
    {
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
        if (!$this->customer_model->get($str)) {
            $this->form_validation->set_message('id_check', 'Customer ' . $str . ' not find');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function create()
    {
        $data['types'] = $this->customerbalance_model->types;
        if ($this->input->post()) {
            $this->form_validation->set_rules('customer_id', 'ID клиента', 'required|callback_id_check|trim');
            $this->form_validation->set_rules('type', 'Тип транзакции', 'required|integer');
            $this->form_validation->set_rules('value', 'Сумма', 'required|numeric');
            $this->form_validation->set_rules('transaction_created_at', 'Дата транзакции', 'required');
            $this->form_validation->set_rules('description', 'Описание транзакции', 'required');
            if ($this->form_validation->run() !== false) {

                $this->customerbalance_model->add_transaction(
                    $this->input->post('customer_id', true),
                    (float)$this->input->post('value'),
                    $this->input->post('description', true),
                    (int)$this->input->post('type'),
                    $this->input->post('transaction_created_at', true),
                    $this->session->userdata('user_id')
                );

                $this->session->set_flashdata('success', lang('text_success'));

                redirect('/autoxadmin/customerbalance');

            } else {
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/customerbalance/create', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {

        $info = $this->customerbalance_model->get($id);

        if ($info) {
            $this->customerbalance_model->delete($id);
            $this->session->set_flashdata('success', 'Транзакция отменена. Баланс пересчитан');
        }


        redirect('/autoxadmin/customerbalance');
    }
}