<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Np{
    public $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $params = $this->CI->settings_model->get_by_key('np');
        $this->CI->load->library('novaposhta', $params);
        $this->CI->load->helper('cookie');
        $this->CI->load->model('delivery/np_model');
        $this->CI->load->language('np');
    }

    public function get_form(){
        $data = [];
        $customer_coockie = get_cookie('customer');
        if($customer_coockie){
            $data['customer'] = json_decode($customer_coockie);
        }
        return $this->CI->load->view('delivery/np/form',$data, TRUE);
    }

    public function save_form($order_id){
        $save['order_id'] = (int)$order_id;

        $save['RecipientCityName'] = (string)$this->CI->input->post('RecipientCityName', true);
        $save['RecipientArea'] = (string)$this->CI->input->post('RecipientArea', true);
        $save['RecipientAreaRegions'] = (string)$this->CI->input->post('RecipientAreaRegions', true);
        $save['RecipientAddressName'] = (string)$this->CI->input->post('RecipientAddressName', true);
        $save['RecipientAddressName2'] = (string)$this->CI->input->post('RecipientAddressName2', true);
        $save['RecipientHouse'] = (string)$this->CI->input->post('RecipientHouse', true);
        $save['RecipientFlat'] = (string)$this->CI->input->post('RecipientFlat', true);

        $this->CI->load->model('delivery/np_model');
        $this->CI->np_model->save_form($save);
        //unset($save['order_id']);
        //$this->CI->db->query("UPDATE ax_order SET address = ".$this->CI->db->escape(implode(',',$save))." WHERE id = '".(int)$order_id."'");
    }

    public function view_form($data){
        $data['order_info'] = $data['order'];
        $data['form_data'] = $this->CI->np_model->get_form_data($data['order']['id']);

        return $this->CI->load->view('admin/delivery/np/view_form',$data, TRUE);
    }

    public function track($data){
        $Ref = $data[0]->Ref;
        $Documents[] = ['DocumentNumber' => $data[0]->IntDocNumber];
        $results = $this->CI->novaposhta->TrackingDocumen($Documents);
        if($results['success']){
            return $results['data'][0];
        }
    }

    public function delivery_price(){
        return;
    }
}
