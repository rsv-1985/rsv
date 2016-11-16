<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Price extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/price');
        $this->load->helper('file');
    }

    public function index(){
        $data = [];
        if($this->input->post()){
            $library_name = $this->input->post('format', true);
            redirect('/autoxadmin/price/get_data?library_name='.$library_name.'&data='.serialize($this->input->post()));
        }

        $formats = get_filenames(APPPATH.'/libraries/priceformat/');
        $data['formats'] = false;
        if($formats){
            foreach ($formats as $format){
                $library_name = explode('.',$format)[0];
                $library_ext = @explode('.',$format)[1];
                if($library_ext == 'php'){
                    $this->load->library('priceformat/'.$library_name);
                    $obj = new $library_name;
                    $data['formats'][$library_name]=$obj->name;
                }
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/price/price', $data);
        $this->load->view('admin/footer');
    }
    
    public function get_data(){
        if($this->input->get()){
            $library_name = $this->input->get('library_name');
            $data = unserialize($this->input->get('data'));
            $this->load->library('priceformat/'.$library_name);
            $obj = new $library_name;
            $obj->get_data($data);
        }
    }

    public function get_price_settings(){
        $library_name = $this->input->post('library_name',true);
        if($library_name){
            $this->load->library('priceformat/'.$library_name);
            $obj = new $library_name;
            $price_settings = $obj->get_price_settings();
            $this->output
                ->set_output($price_settings);
        }
    }
}