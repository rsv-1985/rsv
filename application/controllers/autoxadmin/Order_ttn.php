<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_ttn extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/order_ttn');
        $this->load->model('order_ttn_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/order_ttn/index');
        $config['total_rows'] = $this->order_ttn_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $ttns = $this->order_ttn_model->get_all($config['per_page'], $this->uri->segment(4),false, ['id' => 'DESC']);
        $data['ttns'] = [];
        if($ttns){
            foreach ($ttns as $ttn){
                $this->load->library('delivery/'.$ttn['library']);

                $track = $this->{$ttn['library']}->track(json_decode($ttn['data']));

                $information = json_decode($ttn['data']);

                $data['ttns'][] = [
                    'ttn' => $ttn['ttn'],
                    'order_id' => $ttn['order_id'],
                    'data' => @implode('<br>',$information[0]),
                    'status' => $track['Status'],
                    'delete' => '/delivery/'.$ttn['library'].'/delete_en?id='.$ttn['id'],
                    'print' => '/delivery/'.$ttn['library'].'/print?id='.$ttn['id'],
                ];
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/order_ttn/order_ttn', $data);
        $this->load->view('admin/footer');
    }
}