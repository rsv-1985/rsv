<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends Front_controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('security');
        $this->load->language('product');
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('banner_model');
    }

    public function index($slug){
        $slug = xss_clean($slug);

        $data = $this->product_model->get_by_slug($slug);

        if(!$data){
            $this->output->set_status_header(404, lang('text_page_404'));
            $this->load->view('header');
            $this->load->view('page_404');
            $this->load->view('footer');
            return;
        }

        $data['breadcrumbs'][] = ['href' => base_url(),'text' => lang('text_home')];



        $category_info = $this->category_model->get($data['category_id']);
        if($category_info){
            $data['breadcrumbs'][] = ['href' => base_url('category/'.$category_info['slug']),'text' => $category_info['name']];
        }


        $this->canonical = base_url('product/'.$slug);
        $settings = $this->settings_model->get_by_key('seo_product');
        if($settings){
            $seo = [];
            foreach($settings as $field => $value){
                $seo[$field] = strip_tags(str_replace([
                    '{name}',
                    '{brand}',
                    '{sku}',
                    '{description}',
                ],[
                    $data['name'],
                    $data['brand'],
                    $data['sku'],
                    $data['description'],
                ], $value));
            }
        }

        $this->product_model->update_viewed($data['id']);

        if(mb_strlen($data['h1']) > 0){
            $data['h1'] = $data['h1'];
        }elseif (mb_strlen(@$seo['h1']) > 0){
            $data['h1'] = @$seo['h1'];
        }else{
            $data['h1'] =  $data['name'];
        }

        $data['breadcrumbs'][] = ['href' => base_url('product/'.$data['slug']),'text' => $data['h1']];


        if(mb_strlen($data['title']) > 0){
            $this->title = $data['title'];
        }elseif (mb_strlen(@$seo['title']) > 0){
            $this->title = @$seo['title'];
        }else{
            $this->title = $data['h1'];
        }

        if(mb_strlen($data['meta_description']) > 0){
            $this->description = $data['meta_description'];
        }elseif (mb_strlen(@$seo['description']) > 0){
            $this->description = @$seo['description'];
        }else{
            $this->description = '';
        }

        if(mb_strlen($data['meta_keywords']) > 0){
            $this->keywords = $data['meta_keywords'];
        }elseif (mb_strlen(@$seo['keywords']) > 0){
            $this->keywords = @$seo['keywords'];
        }else{
            $this->keywords = str_replace(' ',',',$this->title);
        }

        $data['prices'] = $this->product_model->get_product_price($data['id'],['status' => true],['price' => 'ASC', 'term' => 'ASC'], true);
        
        $data['image'] =  mb_strlen($data['image']) > 0 ? '/uploads/product/'.$data['image'] : @$data['tecdoc_info']['article']['Image'];


        if(isset($data['tecdoc_info']['article']['Info']) && mb_strlen($data['tecdoc_info']['article']['Info']) > 0){
            $data['description'] .= $data['tecdoc_info']['article']['Info'];
        }

       $data['description'] .= '<br/>'.$seo['description'];

        $data['applicability'] = false;
        if(isset($data['tecdoc_info']['applicability']) && !empty($data['tecdoc_info']['applicability'])){
            $applicability = $data['tecdoc_info']['applicability'];
            foreach ($applicability as $ap){
                $data['applicability'][$ap->Brand][]=$ap;
            }
        }

        $data['components'] = false;
        if(isset($data['tecdoc_info']['components']) && !empty($data['tecdoc_info']['components'])){
            $data['components'] = $data['tecdoc_info']['components'];
        }

        $data['cross'] = false;
        if(isset($data['tecdoc_info']['cross'])){
            $data['cross'] = $data['tecdoc_info']['cross'];
        }


        $data['banner'] = $this->banner_model->get_product();
        $this->load->view('header');
        $this->load->view('product/product', $data);
        $this->load->view('footer');
    }
}