<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Front_controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->helper('security');
        $this->load->helper('typography');
        $this->load->helper('text');
        $this->load->language('news');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('news');
        $config['total_rows'] = $this->news_model->count_all(['status' => true]);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $data['news'] = $this->news_model->get_all($config['per_page'], $this->input->get('per_page'), ['status' => true], ['id' => 'DESC']);

        $this->load->view('header');
        $this->load->view('news/news', $data);
        $this->load->view('footer');
    }

    public function view($slug){
        $slug = xss_clean($slug);
        $news = $this->news_model->get_by_slug($slug);
        if(!$news){
            show_404();
        }
        $data = [];
        $this->setTitle(!empty($news['title']) ? $news['title'] : $news['name']);
        $this->setDescription($news['meta_description']);
        $this->setKeywords($news['meta_keywords']);
        $this->setH1(!empty($news['h1']) ? $news['h1'] : $news['name']);
        $data['h1'] = $this->h1;
        $data['description'] = auto_typography($news['description']);

        $this->setOg('title',$this->title);
        $this->setOg('description',$this->description);
        $this->setOg('url',current_url());

        $this->load->view('header');
        $this->load->view('news/view', $data);
        $this->load->view('footer');
    }

}