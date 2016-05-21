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
        $this->load->language('category');
        $this->load->model('category_model');
        $this->load->model('product_model');
        $this->load->helper('security');
        $this->load->helper('text');
    }

    public function index($slug, $brand = false){
        $slug = xss_clean($slug);
        $category = $this->category_model->get_by_slug($slug);
        if(!$category){
            $this->output->set_status_header(404, lang('text_page_404'));
            $this->load->view('header');
            $this->load->view('page_404');
            $this->load->view('footer');
            return;
        }
        if($brand){
            $settings = $this->settings_model->get_by_key('seo_brand');
            if($settings){
                $seo = [];
                foreach($settings as $field => $value){
                    $seo[$field] = strip_tags(str_replace([
                        '{category}',
                        '{brand}'
                    ],[
                        $category['name'],
                        str_replace('_','/',urldecode($brand)),

                    ], $value));
                }
            }
        }
        //print_r($seo);
        $data = [];
        $this->title = !empty($category['title']) ? $category['title'] : @$seo['title'] ? @$seo['title'] : $category['name'];
        $this->description = @$seo['description'] ? @$seo['description'] : $category['meta_description'];
        $this->keywords = @$seo['meta_keywords'] ? @$seo['meta_keywords'] : $category['meta_keywords'];

        $data['brands'] = $this->category_model->get_brends($category['id']);

        $data['h1'] = !empty($category['h1']) ? $category['h1'] : @$seo['h1'] ? @$seo['h1'] : $category['name'];

        $data['description'] = !$this->uri->segment(3) || !$this->uri->segment(5) ? $category['description'].@$seo['text'] : '';
        $data['slug'] = $category['slug'];
        $this->load->library('pagination');

        if($brand){
            $config['base_url'] = base_url('/category/'.$category['slug'].'/brand/'.$brand);
        }else{
            $config['base_url'] = base_url('/category/'.$category['slug']);
        }

        if($brand){
            $data['products'] = $this->product_model->product_get_all(12, $this->uri->segment(5), ['status' => true, 'category_id' => $category['id'], 'brand' => str_replace('_','/',urldecode($brand))]);
        }else{
            $data['products'] = $this->product_model->product_get_all(12, $this->uri->segment(3), ['status' => true, 'category_id' => $category['id']]);
        }

        $config['total_rows'] = $this->product_model->total_rows;

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



        $this->load->view('header');
        $this->load->view('category/category', $data);
        $this->load->view('footer');
    }

}