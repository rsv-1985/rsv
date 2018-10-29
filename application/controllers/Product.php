<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends Front_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('security');
        $this->load->language('product');
        $this->load->model('product_model');
        $this->load->model('product_attribute_model');
        $this->load->model('category_model');
        $this->load->model('banner_model');
        $this->load->model('delivery_model');
        $this->load->model('payment_model');
        $this->load->model('review_model');
    }

    public function index($slug)
    {
        $slug = xss_clean($slug);

        $data = $this->product_model->get_by_slug($slug);

        if (!$data) {
            $this->output->set_status_header(410, lang('text_page_404'));
            $this->load->view('header');
            $this->load->view('page_404');
            $this->load->view('footer');
            return;
        }

        $data['delivery_methods'] = $this->delivery_model->get_all();
        $data['payment_methods'] = $this->payment_model->get_all();

        $data['breadcrumbs'][] = ['href' => base_url(), 'text' => lang('text_home')];


        $category_info = $this->category_model->get($data['category_id']);
        if ($category_info) {
            $data['breadcrumbs'][] = ['href' => base_url('category/' . $category_info['slug']), 'text' => $category_info['name']];
        }

        //Получаем ценовые предложения
        $data['prices'] = $this->product_model->get_product_price($data);

        $data['one_price'] = false;
        if ($data['prices']) {
            $data['one_price'] = $data['prices'][0];
        }

        $data['banner'] = $this->banner_model->get_product();

        $data['attributes'] = $this->product_attribute_model->get_product_attributes($data['id']);

        $data['applicability'] = false;
        if (isset($data['tecdoc_info']['applicability']) && !empty($data['tecdoc_info']['applicability'])) {
            $applicability = $data['tecdoc_info']['applicability'];
            foreach ($applicability as $ap) {
                $data['applicability'][$ap->Brand][] = $ap;
            }
        }

        $data['components'] = false;
        if (isset($data['tecdoc_info']['components']) && !empty($data['tecdoc_info']['components'])) {
            $data['components'] = $data['tecdoc_info']['components'];
        }

        $data['cross'] = false;
        if (isset($data['tecdoc_info']['cross'])) {
            $data['cross'] = $data['tecdoc_info']['cross'];
        }

        //Если активна опция использовать наименования с текдок
        if (@$this->options['use_tecdoc_name'] && @$data['tecdoc_info']['article']['Name']) {
            $data['name'] = @$data['tecdoc_info']['article']['Name'];
        }

        $settings = $this->settings_model->get_by_key('seo_product');
        if ($settings) {
            $seo = [];
            foreach ($settings as $field => $value) {
                $seo[$field] = str_replace([
                    '{name}',
                    '{brand}',
                    '{sku}',
                    '{description}',
                    '{applicability}'
                ], [
                    $data['name'],
                    $data['brand'],
                    $data['sku'],
                    $data['description'],
                    @implode(', ', array_keys($data['applicability'])),
                ], $value);
            }
        }

        $data['images'] = [];

        $count_img = 0;

        if($data['image']){
            $data['images'][] = [
                'src' => '/uploads/product/'.$data['image'],
                'alt' => @$seo['alt_img']
            ];
            $count_img = 2;
        }

        if($data['tecdoc_info']['images']){
            foreach ($data['tecdoc_info']['images'] as $tc_image){
                $data['images'][] = [
                    'src' => $tc_image->Image,
                    'alt' => $count_img ? @$seo['alt_img'].'-'.$count_img : @$seo['alt_img']
                ];
                $count_img++;
            }
        }

        $data['description'] .= '<br/>' . @$seo['text'];

        $this->product_model->update_viewed($data['id']);

        //$this->canonical = base_url('product/' . $slug);

        if (mb_strlen($data['h1']) > 0) {
            $this->setH1($data['h1']);
        } elseif (mb_strlen(@$seo['h1']) > 0) {
            $this->setH1(@$seo['h1']);
        } else {
            $this->setH1($data['name']);
        }
        $data['h1'] = $this->h1;

        $data['breadcrumbs'][] = ['href' => false, 'text' => $data['h1']];


        if (mb_strlen($data['title']) > 0) {
            $this->setTitle($data['title']);
        } elseif (mb_strlen(@$seo['title']) > 0) {
            $this->setTitle(@$seo['title']);
        } else {
            $this->setTitle($data['h1']);
        }

        if (mb_strlen($data['meta_description']) > 0) {
            $this->setDescription($data['meta_description']);
        } elseif (mb_strlen(@$seo['description']) > 0) {
            $this->setDescription(@$seo['description']);
        } else {
            $this->setDescription();
        }

        if (mb_strlen($data['meta_keywords']) > 0) {
            $this->setKeywords($data['meta_keywords']);
        } elseif (mb_strlen(@$seo['keywords']) > 0) {
            $this->setKeywords(@$seo['keywords']);
        } else {
            $this->setKeywords(str_replace(' ', ',', $this->title));
        }
        $data['tecdoc_attributes'] = false;
        if (isset($data['tecdoc_info']['article']['Info']) && mb_strlen($data['tecdoc_info']['article']['Info']) > 0) {
            $info = explode("<br>", $data['tecdoc_info']['article']['Info']);
            if ($info) {
                foreach ($info as $inf) {
                    $inf = explode(':', $inf);
                    if (@$inf[0] && @$inf[1]) {
                        $data['tecdoc_attributes'][] = ['attribute_name' => $inf[0], 'attribute_value' => @$inf[1]];
                    }
                }
            }
        }

        //Отзывы о товаре
        $data['count_reviews'] = $this->review_model->count_all(['product_id' => $data['id'], 'status' => true]);
        if($data['count_reviews']){
            $data['avg_rating'] = (int)$this->review_model->getAvg(['product_id' => $data['id'], 'status' => true])['rating'];
            $data['reviews'] = $this->review_model->get_all(10, false, ['product_id' => $data['id'], 'status' => true]);
        }

        if ($data['prices']) {
            if ($data['image']) {
                $image = base_url()."/uploads/product/" . $data['image'];
            } else if ($data['tecdoc_info']['images']) {
                $image = $data['tecdoc_info']['images'][0]->Image;
            } else {
                $image = '';
            }
            //Готовим струтурированные данные
            foreach ($data['prices'] as $price) {
                $offers[] = [
                    "@type" => "Offer",
                    "url" => "product/" . $data['slug'],
                    "availability" => format_term($price['term']),
                    "price" => format_currency($price['saleprice'] > 0 ? $price['saleprice'] : $price['price'], false),
                    "url" => base_url('product/' . $data['slug']),
                    "priceCurrency" => $this->currency_model->default_currency['code']
                ];
            }
            $structure = [
                "@context" => "http://schema.org/",
                "@type" => "Product",
                "name" => $this->h1,
                "brand" => $data['brand'],
                "sku" => $data['sku'],
                "image" => $image,
                "offers" => [
                    "@type" => "AggregateOffer",
                    "highPrice" => format_currency(end($data['prices'])['price'], false),
                    "lowPrice" => format_currency($data['prices'][0]['price'], false),
                    "offerCount" => count($data['prices']),
                    "priceCurrency" => $this->currency_model->default_currency['code'],
                    "offers" => $offers
                ]
            ];

            $this->structure = json_encode($structure);
        }

        $this->load->view('header');
        $this->load->view('product/product', $data);
        $this->load->view('footer');
    }
}