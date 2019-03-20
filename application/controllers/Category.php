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
        $this->load->model('mproduct');
        $this->load->helper('security');
        $this->load->helper('text');
    }

    public function view($slug, $filter = false){

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

        $data['brands'] = [];

        $brands = $this->category_model->get_brands($category['id']);

        if($brands){
            foreach ($brands as $brand){
                $brand_slug =  url_title($brand['brand']);
                $data['brands'][$brand_slug] = [
                    'name' => $brand['brand'],
                    'slug' => $brand_slug,
                    'checked' => false
                ];
            }
        }

        $filter_data = [];
        $checked_values = [];

        if($filter){
            $filter_arr = explode(';',$filter);
            foreach ($filter_arr as $f_arr){
                $f = explode('=',$f_arr);

                $key = $f[0];
                $values = explode(',',$f[1]);

                foreach ($values as $value){
                    if($key == 'brand' && isset($data['brands'][$value])){
                        $filter_data['brand'][] = $data['brands'][$value]['name'];
                        $data['brands'][$value]['checked'] = true;
                        $checked_values[] = $value;
                    }else{

                        $attr_id = @explode('_',$key)[1];
                        $val = explode('_',$value);

                        if(@$val[1] && @$attr_id){
                            $filter_data['attr'][$attr_id][] = $val[1];
                            $checked_values[] = $val[1];
                        }
                    }
                }
            }
        }




        $data['checked_values'] = $checked_values;

        $breadcrumbs =  $this->category_model->getBreadcrumb($category['parent_id']);

        $data['breadcrumbs'][] = [
            'name' => lang('text_home'),
            'href' => base_url()
        ];

        if($breadcrumbs){
            foreach ($breadcrumbs as $breadcrumb){
                $data['breadcrumbs'][] = [
                    'name' => $breadcrumb['name'],
                    'href' => base_url('category/'.$breadcrumb['slug'])
                ];
            }
        }

        $data['breadcrumbs'][] = [
            'name' => $category['name'],
            'href' => base_url('category/'.$category['slug'])
        ];

        $data['categories'] = $this->category_model->getCategories($category['id']);




        if(isset($filter_data['brand'])){
            $brand = implode(', ',$filter_data['brand']);
        }else{
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
                        $brand,

                    ], $value));
                }
            }
        }

        $data['attributes'] = [];


        $attributes = $this->product_attribute_model->get_attributes($category['id']);

        $possible_attributes = $this->product_attribute_model->get_possible_attributes($category['id'], $filter_data);

        if($attributes){
            foreach ($attributes as $attribute){
                if(!$attribute['in_filter']){
                    continue;
                }

                if($possible_attributes && !in_array($attribute['attribute_value_id'],$possible_attributes)){
                    $possible = false;
                }else{
                    $possible = true;
                }

                $attr_values[$attribute['attribute_id']][] = [
                   'text' => $attribute['attribute_value'],
                   'slug' => url_title($attribute['attribute_value']).'_'.$attribute['attribute_value_id'],
                   'checked' => @in_array($attribute['attribute_value_id'], $checked_values),
                    'possible' => $possible
                ];
            }

            foreach ($attributes as $attribute){
                $data['attributes'][$attribute['attribute_id']] = [
                    'name' => $attribute['attribute'],
                    'slug' => url_title($attribute['attribute']).'_'.$attribute['attribute_id'],
                    'max_height' => $attribute['max_height'],
                    'values' => $attr_values[$attribute['attribute_id']]
                ];
            }
        }

        if($brand){
            $this->setH1(@$seo['h1']);
            $data['h1'] = $this->h1;
            $this->setTitle(@$seo['title']);
            $this->setDescription(@$seo['description']);
            $this->setKeywords(@$seo['keywords']);

            $this->setOg('title',@$seo['title']);
            $this->setOg('description',@$seo['description']);
            $this->setOg('url',current_url());
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

            $this->setOg('title',$this->title);
            $this->setOg('description',$this->description);
            $this->setOg('url',current_url());
        }

        if($filter_data || $this->uri->segment(5)){
            $this->canonical = base_url('category/'.$category['slug']);
            $data['description'] = '';
        }else{
            $data['description'] = $category['description'].@$seo['text'];
        }

        $data['slug'] = $category['slug'];
        $this->load->library('pagination');

        if($filter){
            $config['base_url'] = base_url('/category/'.$category['slug'].'/'.$filter);
        }else{
            $config['base_url'] = base_url('/category/'.$category['slug']);
        }

        $data['products'] = [];

        $products = $this->mproduct->getByCategory($category['id'], $filter_data, 12, $filter_data ? $this->uri->segment(4) : $this->uri->segment(3));

        if($products){
            //Массово получаем инфу с текдока
            foreach ($products as $product){
                $key = md5($product->sku.$product->getBrand());
                $td[$key] = ['sku' => $product->sku, 'brand' => $product->getBrand()];
            }

            $tecdoc_info_array = (array)$this->tecdoc->getArticleArray($td);


            foreach ($products as $product){
                $key = md5($product->sku.$product->getBrand());

                $name = $product->getName();
                if(@$this->options['use_tecdoc_name'] && @$tecdoc_info_array[$key]->Name){
                    $name = $tecdoc_info_array[$key]->Name;
                }

                $image = '';

                $images = $product->getImages();
                if($images){
                    $image = $images[0];
                }

                $attributes = [];

                $product_attributes = $product->getAttributes();
                if($product_attributes){
                    foreach ($product_attributes as $product_attribute){
                        if($product_attribute['in_short_description']){
                            $attributes[] = $product_attribute;
                        }
                    }
                }

                $prices_text = '';
                $product_prices = $product->getPrices(true);
                if($product_prices){
                    $price_from = format_currency($product_prices[0]['saleprice'] > 0 ? $product_prices[0]['saleprice'] : $product_prices[0]['price']);
                    $price_to = format_currency(end($product_prices)['saleprice'] > 0 ? end($product_prices)['saleprice'] : end($product_prices)['price']);

                    if($price_from == $price_to){
                        $prices_text = $price_from;
                    }else{
                        $prices_text = $price_from.'-'.$price_to;
                    }
                }


                $data['products'][] = [
                    'id' => $product->id,
                    'name' => $name,
                    'sku' => $product->sku,
                    'brand' => $product->getBrand(),
                    'slug' => $product->slug,
                    'image' => $image,
                    'attributes' => $attributes,
                    'prices_text' => $prices_text,
                    'prices' => $product_prices
                ];
            }
        }

        $config['total_rows'] = $this->mproduct->total_rows;

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

    public function index(){
        $data['categories'] = $this->category_model->getCategories();
        $this->load->view('header');
        $this->load->view('category/index', $data);
        $this->load->view('footer');
    }

}