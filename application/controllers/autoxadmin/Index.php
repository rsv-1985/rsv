<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Admin_controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
    }

    public function index(){
        $_GET['status'] = $this->orderstatus_model->get_default()['id'];
        $data['new_orders'] = $this->order_model->order_get_all(50);

        $data['new_order'] = $this->db->where(['status' => $this->orderstatus_model->get_default()['id']])->count_all_results('order');
        $data['new_vin'] = $this->db->where(['status' => 0])->count_all_results('vin');
        $data['new_customer'] = $this->db->where(['status' => false])->count_all_results('customer');

        //Проверяем статус обновлений
        $data['cms_updates'] = json_decode(file_get_contents('https://api.autox.pro/cmsupdates/status?site='.$_SERVER['HTTP_HOST']));

        $this->load->view('admin/header');
        $this->load->view('admin/index',$data);
        $this->load->view('admin/footer');
    }

    public function cache(){
        $this->clear_cache();
        $this->session->set_flashdata('success', lang('text_success_cache'));
        redirect('autoxadmin');
    }

    public function updatesystem(){
        $cms_updates = json_decode(file_get_contents('https://api.autox.pro/cmsupdates?site='.$_SERVER['HTTP_HOST']));
        if($cms_updates->status){
            exec($cms_updates->command,$output);
            if($output){
                foreach ($output as $item){
                    echo $item.'<br>';
                }
            }
        }else{
            exit('Доступ запрещен к обновлениям.');
        }
    }
}