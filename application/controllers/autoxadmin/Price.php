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
        $this->load->model('product_model');
        $this->load->model('supplier_model');
        $this->load->model('category_model');
        $this->load->helper('file');
    }

    public function index(){
        $data = [];
        if($this->input->post()){
            
            $where = [];
            if($this->input->post('category_id')){
                $where['category_id'] = (int)$this->input->post('category_id');
            }
            if($this->input->post('supplier_id')){
                $where['supplier_id'] = (int)$this->input->post('supplier_id');
            }
            if($this->input->post('saleprice')){
                $where['saleprice >'] = 0;
            }
            
            $where['offset'] = 0;
            
            $library_name = $this->input->post('format', true);
            redirect('/autoxadmin/price/get_data?library_name='.$library_name.'&where='.serialize($where));
        }
        $data['suppliers'] = $this->supplier_model->supplier_get_all();
        $data['categories'] = $this->category_model->admin_category_get_all();
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
            $where = unserialize($this->input->get('where'));
            $this->load->library('priceformat/'.$library_name);
            $obj = new $library_name;
            $obj->get_data($where);
        }
        
    }
}