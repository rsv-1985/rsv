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
        $this->load->model('banner_model');
    }

    public function index($slug){
        $slug = xss_clean($slug);
        $product = $this->product_model->get_by_slug($slug);

        if(!$product){
            $this->output->set_status_header(404, lang('text_page_404'));
            $this->load->view('header');
            $this->load->view('page_404');
            $this->load->view('footer');
            return;
        }
        $settings = $this->settings_model->get_by_key('seo_product');
        if($settings){
            $seo = [];
            foreach($settings as $field => $value){
                $seo[$field] = strip_tags(str_replace([
                    '{name}',
                    '{brand}',
                    '{sku}',
                    '{description}',
                    '{excerpt}'
                ],[
                    $product['name'],
                    $product['brand'],
                    $product['sku'],
                    $product['description'],
                    $product['excerpt']
                ], $value));
            }
        }
        $this->product_model->update_viewed($product['slug']);

        $data['h1'] = mb_strlen($product['h1']) > 0 ? $product['h1'] : mb_strlen(@$seo['h1']) > 0 ? @$seo['h1'] : $product['name'];
        $this->title = mb_strlen($product['title']) > 0 ? $product['title'] : mb_strlen(@$seo['title']) > 0 ? @$seo['title'] : $data['h1'];
        $this->description =  mb_strlen($product['meta_description']) > 0 ? $product['meta_description'] : mb_strlen(@$seo['description']) > 0 ? @$seo['description'] : '';
        $this->keywords =  mb_strlen($product['meta_keywords']) > 0 ? $product['meta_keywords'] : mb_strlen(@$seo['keywords']) > 0 ? @$seo['keywords'] : '' ;

        $data['brand'] = $product['brand'];
        $data['price'] = format_currency($product['price']);
        $data['saleprice'] = format_currency($product['saleprice']);
        $data['excerpt'] = $product['excerpt'];
        $data['sku'] = $product['sku'];
        $data['quantity'] = $product['quantity'];
        $data['description'] = $product['description'].'<br>';
        if(isset($product['tecdoc_info']['article']['Info']) && mb_strlen($product['tecdoc_info']['article']['Info']) > 0){
            $data['description'] .= $product['tecdoc_info']['article']['Info'];
        }
        $data['slug'] = $product['slug'];

        $data['image'] = '/assets/themes/default/img/no_image.png';
        if(isset($product['tecdoc_info']['article']['Image']) && mb_strlen($product['tecdoc_info']['article']['Image']) > 0){
            $data['image'] =  $product['tecdoc_info']['article']['Image'];
        }

        $data['applicability'] = false;
        if(isset($product['tecdoc_info']['applicability']) && !empty($product['tecdoc_info']['applicability'])){
            $data['applicability'] = $product['tecdoc_info']['applicability'];
        }

        $data['components'] = false;
        if(isset($product['tecdoc_info']['components']) && !empty($product['tecdoc_info']['components'])){
            $data['components'] = $product['tecdoc_info']['components'];
        }

        $data['banner'] = $this->banner_model->get_product();
        $this->load->view('header');
        $this->load->view('product/product', $data);
        $this->load->view('footer');
    }
}