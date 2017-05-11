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


        $this->setTitle(!empty($data['title']) ? $data['title'] : $data['name']);
        $this->setDescription($data['meta_description']);
        $this->setKeywords($data['meta_keywords']);
        $this->setH1($data['h1']);


        if(empty($data['h1'])){
            $this->setH1($data['name']);
        }

        $data['h1'] = $this->h1;

        $data['parent'] = $this->page_model->get_parent($data['id']);
        $data['main'] = $this->page_model->get_main($data['parent_id']);
        $this->load->view('header');
        $this->load->view('page/page',$data);
        $this->load->view('footer');
    }
}