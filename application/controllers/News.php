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
    }

    public function index($slug){
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
        $data['description'] = $news['description'];

        $this->load->view('header');
        $this->load->view('news/news', $data);
        $this->load->view('footer');
    }

}