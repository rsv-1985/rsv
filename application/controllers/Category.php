<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class category extends Front_controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
        $this->load->model('product_model');
        $this->load->helper('security');
        $this->load->helper('text');
    }

    public function index($slug){
        $slug = xss_clean($slug);
        $category = $this->category_model->get_by_slug($slug);
        if(!$category){
            $this->output->set_status_header(404, lang('text_page_404'));
            $this->load->view('header');
            $this->load->view('page_404');
            $this->load->view('footer');
            return;
        }
        $data = [];
        $this->title = !empty($category['title']) ? $category['title'] : $category['name'];
        $this->description = $category['meta_description'];
        $this->keywords = $category['meta_keywords'];

        $data['h1'] = !empty($category['h1']) ? $category['h1'] : $category['name'];
        $data['description'] = !$this->uri->segment(3) ? $category['description'] : '';

        $this->load->library('pagination');

        $config['base_url'] = base_url('/category/'.$category['slug']);
        $config['total_rows'] = $this->product_model->count_all(['status' => true, 'category_id' => $category['id']]);
        $config['per_page'] = 12;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

        $data['products'] = $this->product_model->product_get_all($config['per_page'], $this->uri->segment(3), ['status' => true, 'category_id' => $category['id']]);


        $this->load->view('header');
        $this->load->view('category/category', $data);
        $this->load->view('footer');
    }

}