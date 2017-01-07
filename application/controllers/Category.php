<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Front_controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('category');
        $this->load->model('category_model');
        $this->load->model('product_model');
        $this->load->model('product_attribute_model');
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

        $data = [];
        $data['brands'] = $this->category_model->get_brends($category['id']);

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
                        $data['brands'][$brand],

                    ], $value));
                }
            }
        }



        $filter_products_id = false;

        $filters = false;
        if($this->input->get()){
            foreach ($this->input->get() as $filter => $value){
                $filters[] = $filter;
            }
            $filter_products_id = $this->product_attribute_model->get_filter_products_id($filters);
        }


        $data['attributes'] = false;

        $attributes = $this->product_attribute_model->get_attributes($category['id'], $filter_products_id);

        if($attributes){
            foreach ($attributes as $attribute){
                $data['attributes'][$attribute['attribute_name']][] = $attribute;
            }
        }

        if(mb_strlen($category['h1']) > 0){
            $data['h1'] = $category['h1'];
        }elseif (mb_strlen(@$seo['h1']) > 0){
            $data['h1'] = @$seo['h1'];
        }else{
            $data['h1'] =  $category['name'];
        }

        if(mb_strlen($category['title']) > 0){
            $this->title = $category['title'];
        }elseif (mb_strlen(@$seo['title']) > 0){
            $this->title = @$seo['title'];
        }else{
            $this->title = $data['h1'];
        }

        if(mb_strlen($category['meta_description']) > 0){
            $this->description = $category['meta_description'];
        }elseif (mb_strlen(@$seo['description']) > 0){
            $this->description = @$seo['description'];
        }else{
            $this->description = '';
        }

        if(mb_strlen($category['meta_keywords']) > 0){
            $this->keywords = $category['meta_keywords'];
        }elseif (mb_strlen(@$seo['keywords']) > 0){
            $this->keywords = @$seo['keywords'];
        }else{
            $this->keywords = str_replace(' ',',',$this->title);
        }

        $data['description'] = !$this->uri->segment(3) || !$this->uri->segment(5) ? $category['description'].@$seo['text'] : '';
        $data['slug'] = $category['slug'];
        $this->load->library('pagination');

        if($brand){
            $config['base_url'] = base_url('/category/'.$category['slug'].'/brand/'.$brand);
        }else{
            $config['base_url'] = base_url('/category/'.$category['slug']);
        }

        if($brand){
            $data['products'] = $this->product_model->product_get_all(12, $this->uri->segment(5), ['status' => true, 'product.category_id' => $category['id'], 'brand' => $data['brands'][$brand]], false, $filter_products_id);
        }else{
            $data['products'] = $this->product_model->product_get_all(12, $this->uri->segment(3), ['status' => true, 'product.category_id' => $category['id']], false, $filter_products_id);
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
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        if($this->pagination->rel_prev){
            $this->rel_prev = $this->pagination->rel_prev;
        }
        if($this->pagination->rel_next){
            $this->rel_next = $this->pagination->rel_next;
        }

        $this->load->view('header');
        $this->load->view('category/category', $data);
        $this->load->view('footer');
    }

}