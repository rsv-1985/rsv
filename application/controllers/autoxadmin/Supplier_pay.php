<?php

/**
 * Created by PhpStorm.
 * User: serg
 * Date: 05.06.2017
 * Time: 13:10
 */

class Supplier_pay extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplier_model');
        $this->load->model('admin/msupplier_pay');
        $this->load->language('admin/supplier_pay');
    }

    public function index(){
        $data['pays'] = array();
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/supplier_pay/index');
        $config['per_page'] = 30;
        $data['pays'] = $this->msupplier_pay->getPays($config['per_page'], $this->uri->segment(4));

        $config['total_rows'] = $this->msupplier_pay->total_rows;
        $config['reuse_query_string'] = true;
        $data['suppliers'] = $this->supplier_model->supplier_get_all();
        $this->pagination->initialize($config);

        $this->load->view('admin/header');
        $this->load->view('admin/supplier_pay/index', $data);
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
        $data['suppliers'] = $this->supplier_model->supplier_get_all();
        $data['id'] = $id;

        if($id){
            $data['action'] = '/autoxadmin/supplier_pay/edit/'.$id;
            $pay_info = $this->msupplier_pay->getPay($id);
        }else{
            $data['action'] = '/autoxadmin/supplier_pay/create';
            $pay_info = false;
        }

        if($this->input->post('supplier_id')){
            $data['supplier_id'] = $this->input->post('supplier_id', true);
        }else if($pay_info){
            $data['supplier_id'] = $pay_info['supplier_id'];
        }else{
            $data['supplier_id'] = '';
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
            $data['date'] = date('Y-m-d');
        }

        if($this->input->post('time')){
            $data['time'] = $this->input->post('time', true);
        }else if($pay_info){
            $data['time'] = date('H:i', strtotime($pay_info['transaction_date']));
        }else{
            $data['time'] = date('H:i');
        }

        if($this->input->post('comment')){
            $data['comment'] = $this->input->post('comment', true);
        }else if($pay_info){
            $data['comment'] = $pay_info['comment'];
        }else{
            $data['comment'] = '';
        }

        $this->load->view('admin/header');
        $this->load->view('admin/supplier_pay/form', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->msupplier_pay->delete($id);

        redirect('/autoxadmin/supplier_pay');
    }

    protected function _save_data($id = false){

        $save['supplier_id'] = (int)$this->input->post('supplier_id');
        $save['amount'] = (float)$this->input->post('amount');
        $save['transaction_date'] = date('Y-m-d H:i:s',strtotime($this->input->post('date', true).' '.$this->input->post('time', true)));
        $save['comment'] = $this->input->post('comment', true);
        $this->msupplier_pay->insert($save,$id);

        $this->session->set_flashdata('success', lang('text_success'));

        redirect('/autoxadmin/supplier_pay');
    }
}