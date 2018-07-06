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
            $this->output->set_status_header(410, lang('text_page_404'));
            $this->load->view('header');
            $this->load->view('page_404');
            $this->load->view('footer');
            return;
        }

        $data = [];
        $data['brands'] = $this->category_model->get_brands($category['id']);
        if($brand && !isset($data['brands'][$brand])){
            $brand = false;
        }
        if($brand){
            $settings = $this->settings_model->get_by_key('seo_brand');
            if($settings){
                $seo = [];
                foreach($settings as $field => $value){
                    $seo[$field] = trim(str_replace([
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

        if($brand){
            $this->setH1(@$seo['h1']);
            $data['h1'] = $this->h1;
            $this->setTitle(@$seo['title']);
            $this->setDescription(@$seo['description']);
            $this->setKeywords(@$seo['keywords']);
        }else{
            if(mb_strlen($category['h1']) > 0){
                $this->setH1($category['h1']);
            }elseif (mb_strlen(@$seo['h1']) > 0){
                $this->setH1(@$seo['h1']);
            }else{
                $this->setH1($category['name']);
            }

            $data['h1'] = $this->h1;

            if(mb_strlen($category['title']) > 0){
                $this->setTitle($category['title']);
            }elseif (mb_strlen(@$seo['title']) > 0){
                $this->setTitle(@$seo['title']);
            }else{
                $this->setTitle($data['h1']);
            }

            if(mb_strlen($category['meta_description']) > 0){
                $this->setDescription($category['meta_description']);
            }elseif (mb_strlen(@$seo['description']) > 0){
                $this->setDescription(@$seo['description']);
            }else{
                $this->setDescription();
            }

            if(mb_strlen($category['meta_keywords']) > 0){
                $this->setKeywords($category['meta_keywords']);
            }elseif (mb_strlen(@$seo['keywords']) > 0){
                $this->setKeywords(@$seo['keywords']);
            }else{
                $this->setKeywords(str_replace(' ',',',$this->title));
            }
        }

        if($this->uri->segment(3) || $this->uri->segment(5)){
            $this->canonical = base_url('category/'.$category['slug']);
            $data['description'] = '';
        }else{
            $data['description'] = $category['description'].@$seo['text'];
        }

        $data['slug'] = $category['slug'];
        $this->load->library('pagination');

        if($brand){
            $config['base_url'] = base_url('/category/'.$category['slug'].'/brand/'.$brand);
        }else{
            $config['base_url'] = base_url('/category/'.$category['slug']);
        }

        if($brand){
            $products = $this->product_model->product_get_all(12, $this->uri->segment(5), ['product.category_id' => $category['id'], 'brand' => $data['brands'][$brand]], false, $filter_products_id);
        }else{
            $products = $this->product_model->product_get_all(12, $this->uri->segment(3), ['product.category_id' => $category['id']], false, $filter_products_id);
        }

        //Если активна опция использовать наименования с текдок
        if($this->options['use_tecdoc_name'] && $products){
            foreach ($products as &$product){
                if(@$product['tecdoc_info']['article']['Name']){
                    $product['name'] = $product['tecdoc_info']['article']['Name'];
                }
            }
        }

        $data['products'] = $products;

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