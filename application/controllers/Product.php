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
        $this->load->language('product');
        $this->load->model('product_model');
        $this->load->model('mproduct');
        $this->load->model('product_attribute_model');
        $this->load->model('category_model');
        $this->load->model('banner_model');
        $this->load->model('delivery_model');
        $this->load->model('payment_model');
        $this->load->model('review_model');
    }

    public function index($slug)
    {
        $product = $this->mproduct->getBySlug($slug);

        if (!$product) {
            $this->output->set_status_header(410, lang('text_page_404'));
            $this->load->view('header');
            $this->load->view('page_404');
            $this->load->view('footer');
            return;
        }

        $data['delivery_methods'] = $this->delivery_model->get_all();
        $data['payment_methods'] = $this->payment_model->get_all();

        $data['breadcrumbs'][] = ['href' => base_url(), 'text' => lang('text_home')];


        $category_info = $this->category_model->get($product->category_id);


        if ($category_info) {
            $category_breadcrumbs = $this->category_model->getBreadcrumb($category_info['parent_id']);

            if($category_breadcrumbs){
                foreach ($category_breadcrumbs as $category_breadcrumb){
                    $data['breadcrumbs'][] = ['href' => base_url('category/'.$category_breadcrumb['slug']), 'text' => $category_breadcrumb['name']];
                }
            }
            $data['breadcrumbs'][] = ['href' => base_url('category/' . $category_info['slug']), 'text' => $category_info['name']];
        }

        //Получаем ценовые предложения
        $data['prices'] = $product->getPrices(true);

        $data['one_price'] = false;
        if ($data['prices']) {
            $data['one_price'] = $data['prices'][0];
        }

        $data['banner'] = $this->banner_model->get_product();

        $data['attributes'] = $this->product_attribute_model->get_product_attributes($product->id);

        $data['applicability'] = false;
        if ($applicability = $product->getApplicability()) {
            foreach ($applicability as $ap) {
                $data['applicability'][$ap->Brand][] = $ap;
            }
        }

        $data['components'] = $product->getComponents();


        $data['cross'] = [];
        $crosses = $product->getCrosses();
        if($crosses){
            foreach ($crosses as $cross){
                if($prices = $cross->getPrices(true)){

                    $data['cross'][] = [
                        'id' => $cross->id,
                        'sku' => $cross->sku,
                        'brand' => $cross->getBrand(),
                        'name' => $cross->getName(),
                        'slug' => $cross->slug,
                        'prices' => $prices
                    ];
                }
            }
        }

        $data['oecross'] = [];
        $oecross = $product->getOeCross();
        if($oecross){
            $data['oecross'] = $oecross;
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
                    $product->getName(),
                    $product->getBrand(),
                    $product->sku,
                    $product->description,
                    @implode(', ', array_keys($data['applicability'])),
                ], $value);
            }
        }

        $data['images'] = [];
        $images = $product->getImages();
        $count_img = 0;
        foreach ($images as $img){
            $data['images'][] = [
                'src' => $img,
                'alt' => $count_img ? @$seo['alt_img'].'-'.$count_img : @$seo['alt_img']
            ];

            $count_img++;
        }

        $data['id'] = $product->id;
        $data['brand'] = $product->getBrand();
        $data['sku'] = $product->sku;
        $data['slug'] = $product->slug;
        $data['description'] = $product->description . '<br/>' . @$seo['text'];


        //$this->canonical = base_url('product/' . $slug);

        if (mb_strlen($product->h1) > 0) {
            $this->setH1($product->h1);
        } elseif (mb_strlen(@$seo['h1']) > 0) {
            $this->setH1(@$seo['h1']);
        } else {
            $this->setH1($product->getName());
        }

        $data['h1'] = $this->h1;

        $data['breadcrumbs'][] = ['href' => false, 'text' => $data['h1']];


        if ($product->title) {
            $this->setTitle($product->title);
        } elseif (mb_strlen(@$seo['title']) > 0) {
            $this->setTitle(@$seo['title']);
        } else {
            $this->setTitle($data['h1']);
        }

        if (mb_strlen($product->meta_description) > 0) {
            $this->setDescription($product->meta_description);
        } elseif (mb_strlen(@$seo['description']) > 0) {
            $this->setDescription(@$seo['description']);
        } else {
            $this->setDescription();
        }

        if (mb_strlen($product->meta_keywords) > 0) {
            $this->setKeywords($product->meta_keywords);
        } elseif (mb_strlen(@$seo['keywords']) > 0) {
            $this->setKeywords(@$seo['keywords']);
        } else {
            $this->setKeywords(str_replace(' ', ',', $this->title));
        }
        $data['tecdoc_attributes'] = false;
        if ($tecdoc_attributes = $product->getInfo()) {
            $info = explode("<br>", $tecdoc_attributes);
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
        $data['count_reviews'] = $product->getCountReviews();

        if($data['count_reviews']){
            $data['avg_rating'] = $product->getRating();
            $data['reviews'] = $product->getReviews();
        }




            if ($data['images']) {
                $image = $data['images'][0]['src'];
            } else {
                $image = base_url('/assets/themes/default/img/no_image.png');
            }

            $structure = [
                "@context" => "http://schema.org/",
                "@type" => "Product",
                "description" => $data['description'],
                "name" => $this->h1,
                "brand" => $data['brand'],
                "sku" => $data['sku'],
                "image" => $image,
                "itemCondition" => 'new',
                "url" => base_url('product/'.$data['slug'])
            ];

            if($data['one_price']){
                $structure['offers'] = [
                    "@type" => "Offer",
                    "availability" => "http://schema.org/InStock",
                    "price" =>  format_currency($data['one_price']['saleprice'] > 0 ? $data['one_price']['saleprice'] : $data['one_price']['price'], false),
                    "priceCurrency" => $this->currency_model->default_currency['code'],
                    "url" => base_url('product/'.$data['slug'])
                ];
            }else{
                $structure['offers'] = [
                    "@type" => "Offer",
                    "availability" => "http://schema.org/OutOfStock",
                    "price" => 0,
                    "priceCurrency" => $this->currency_model->default_currency['code'],
                    "url" => base_url('product/'.$data['slug'])
                ];
            }

            if($data['count_reviews']){
                foreach ($data['reviews'] as $review){
                    $structure_review[] = [
                        "@type" => "Review",
                        "author" => $review['author'],
                        "datePublished" => $review['created_at'],
                        "description" => $review['text'],
                        "name" => $review['author'],
                        "reviewRating" => [
                          "@type" => "Rating",
                          "bestRating" => "5",
                          "ratingValue" => $review['rating'],
                          "worstRating" => "1"
                      ]
                    ];
                }

                $structure['review'] = $structure_review;

                $structure["aggregateRating"] = [
                    "@type" => "AggregateRating",
                    "ratingValue" => $data['avg_rating'],
                    "reviewCount" => $data['count_reviews']
                ];
            }

            $this->structure = json_encode($structure);



        $this->setOg('title',$this->title);
        $this->setOg('description',$this->description);
        $this->setOg('url',current_url());
        if($data['images']){
            $this->setOg('image',base_url('image').'?img='.$data['images'][0]['src']);
        }

        $this->load->view('header');
        $this->load->view('product/product', $data);
        $this->load->view('footer');
    }
}