<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Front_controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('page_model');
        $this->load->language('page');
    }

    public function index($slug){
        $slug = $this->security->xss_clean($slug);
        $data = [];
        $data = $this->page_model->get_by_slug($slug);
        if(!$data){
            $this->output->set_status_header(404, lang('text_page_404'));
            $this->load->view('header');
            $this->load->view('page_404');
            $this->load->view('footer');
            return;
        }

        $this->title = !empty($data['title']) ? $data['title'] : $data['name'];
        $this->description = $data['meta_description'];
        $this->keywords = $data['meta_keywords'];

        if(empty($data['h1'])){
            $data['h1'] = $data['name'];
        }

        $widgets = [
            '{vin}' => $this->load->view('widget/vin',null,TRUE),
        ];

        foreach ($widgets as $key => $widget){
            $data['description'] = str_replace($key, $widget, $data['description']);
        }

        $data['parent'] = $this->page_model->get_parent($data['id']);
        $data['main'] = $this->page_model->get_main($data['parent_id']);
        $this->load->view('header');
        $this->load->view('page/page',$data);
        $this->load->view('footer');
    }
}