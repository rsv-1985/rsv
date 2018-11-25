<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Front_controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->language('index');
        $this->load->model('product_model');
        $this->load->model('banner_model');
        $this->load->model('news_model');
    }

    public function index()
	{
        $data = [];
        $settings = $this->settings_model->get_by_key('main_settings');
        if($settings){
            $this->setTitle($settings['title']);
            $this->setDescription($settings['meta_description']);
            $this->setKeywords($settings['meta_keywords']);
        }
        $data['name'] = $settings['name'];
        $data['description'] = $settings['description'];
        $data['manufacturers'] = false;
        $data['slider'] = $this->banner_model->get_slider();
        $data['box'] = $this->banner_model->get_box();
        $data['carousel'] = $this->banner_model->get_carousel();
        $data['news'] = $this->news_model->get_all(5,false,['status' => true],['id' => 'DESC']);

        if(@$this->options['novelty']){
            $data['novelty'] = false;//$this->product_model->get_novelty();
        }else{
            $data['novelty'] = false;
        }

        if(@$this->options['top_sellers']){
            $data['top_sellers'] = false;//$this->product_model->top_sellers();
        }else{
            $data['top_sellers'] = false;
        }

        $data['recently_viewed'] = false;
        $data['top_new'] = false;
        $data['catalog'] = false;

        if(!$this->config->item('catalog')){
            $manufacturers = $this->tecdoc->getManufacturer();
            if($manufacturers){
                $settings_tecdoc_manufacturer = $this->settings_model->get_by_key('tecdoc_manufacturer');
                $array_manuf = [];
                foreach($manufacturers as $item){
                    if($settings_tecdoc_manufacturer){
                        if(isset($settings_tecdoc_manufacturer[url_title($item->Name)]) && @$settings_tecdoc_manufacturer[url_title($item->Name)]['status']){
                            $array_manuf[] = [
                                'slug' => url_title($item->Name).'_'.$item->ID_mfa,
                                'ID_mfa' => $item->ID_mfa,
                                'name' => $settings_tecdoc_manufacturer[url_title($item->Name)]['name'] ? $settings_tecdoc_manufacturer[url_title($item->Name)]['name'] : $item->Name,
                                'logo' => $settings_tecdoc_manufacturer[url_title($item->Name)]['logo'] ? $settings_tecdoc_manufacturer[url_title($item->Name)]['logo'] : '/uploads/model/'.str_replace('Ë','E',$item->Name).'.png',
                            ];
                        }
                    }else{
                        if(file_exists('./uploads/model/'.str_replace('Ë','E',$item->Name).'.png')){
                            $array_manuf[] = [
                                'slug' => url_title($item->Name).'_'.$item->ID_mfa,
                                'ID_mfa' => $item->ID_mfa,
                                'name' => $item->Name,
                                'logo' => strlen($item->Logo) > 0 ? $item->Logo : '/uploads/model/'.str_replace('Ë','E',$item->Name).'.png',
                            ];
                        }
                    }
                }
                $data['catalog'] = $this->load->view('widget/catalog',['manufacturers' => $array_manuf], true);
            }
        }else{
            $catalog_settings = $this->config->item('catalog');
            if(isset($catalog_settings['views']) && isset($catalog_settings['manufacturers'])){
                $data['catalog'] = $this->load->view($catalog_settings['views'],['manufacturers' => $catalog_settings['manufacturers']],true);
            }
        }

        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
	}
    
    public function page_404(){
        $this->output->set_status_header(404, lang('text_page_404'));
        $this->load->view('header');
        $this->load->view('page_404');
        $this->load->view('footer');
    }

    public function test(){
	    $this->load->library('api_delivery/np');
	    echo $this->np->get_form();
    }
}
