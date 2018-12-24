<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Waybill extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/waybill');
        $this->load->model('waybill_model');
        $this->load->model('delivery_model');
        $this->load->model('payment_model');
        $this->load->model('orderstatus_model');
        $this->load->model('invoice_model');
    }

    private function _get_key($item){
        return url_title($item['delivery_method_id'].$item['first_name'].$item['last_name'].$item['address']);
    }
    private function _get_address($delivery_method_id, $items){
        $results = [];
        $invoices = [];
        foreach ($items as $item){
            if($item['delivery_method_id'] == $delivery_method_id){
                $key = $this->_get_key($item);
                $invoices = [];
                $invoices[] = $item['invoice_id'];
                if($item['RecipientCityName']){
                    $address = $item['RecipientCityName'].' '.$item['RecipientAddressName'];
                }else{
                    $address = $item['address'];
                }
                $results[$key] = [
                    'first_name' => $item['first_name'],
                    'last_name' => $item['last_name'],
                    'address' => $address,
                    'telephone' => $item['telephone'],
                    'products' => $this->_get_products($delivery_method_id,$key,$items),
                    'invoice' => $item['invoice_id']
                ];
            }
        }

        return $results;
    }

    private function _get_delivery($items){
        $results = [];

        foreach ($items as $item){
            $results[$item['delivery_method_id']] = $item['delivery_method'];
        }

        return $results;
    }

    private function _get_products($delivery_method_id, $key, $items){
        $results = [];

        foreach ($items as $item){
            $item_key = $this->_get_key($item);

            if($item['delivery_method_id'] == $delivery_method_id && $key == $item_key){
                $results[] = $item;
            }
        }
        return $results;
    }

    public function index(){
        $data = [];
        $data['results'] = [];

        $results = $this->waybill_model->getInvoices();

        //Формируем ключ группы
        $delivery_group = $this->_get_delivery($results);

        foreach ($delivery_group as $delivery_method_id => $delivery_method) {
            $data['results'][] = [
                'delivery_method' => $delivery_method,
                'addresses' => $this->_get_address($delivery_method_id,$results)
            ];
        }

        $data['invoice_statuses'] = $this->invoice_model->statuses;

        $data['order_statuses'] = $this->orderstatus_model->status_get_all();

        if($this->input->get('print')){
            $this->load->view('admin/waybill/print', $data);
        }else{
            $this->load->view('admin/header');
            $this->load->view('admin/waybill/waybill', $data);
            $this->load->view('admin/footer');
        }

    }

}