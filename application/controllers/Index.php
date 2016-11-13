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
            $this->title = $settings['title'];
            $this->description = $settings['meta_description'];
            $this->keywords = $settings['meta_keywords'];
        }
        $data['name'] = $settings['name'];
        $data['description'] = $settings['description'];
        $data['manufacturers'] = false;
        $data['slider'] = $this->banner_model->get_slider();
        $data['box'] = $this->banner_model->get_box();
        $data['carousel'] = $this->banner_model->get_carousel();
        $data['news'] = $this->news_model->get_all(10,false,['status' => true],['id' => 'DESC']);

        if(@$this->options['novelty']){
            $data['novelty'] = $this->product_model->get_novelty();
        }else{
            $data['novelty'] = false;
        }

        if(@$this->options['top_sellers']){
            $data['top_sellers'] = $this->product_model->top_sellers();
        }else{
            $data['top_sellers'] = false;
        }

        $data['recently_viewed'] = false;
        $data['top_new'] = false;

        $manufacturers = $this->tecdoc->getManufacturer();
        if($manufacturers){
            $settings_tecdoc_manufacturer = $this->settings_model->get_by_key('tecdoc_manufacturer');
            $data['manufacturers'] = [];
            foreach($manufacturers as $item){
                if($settings_tecdoc_manufacturer){
                    if(isset($settings_tecdoc_manufacturer[$item->ID_mfa])){
                        $data['manufacturers'][] = [
                            'slug' => url_title($item->Name).'_'.$item->ID_mfa,
                            'ID_mfa' => $item->ID_mfa,
                            'name' => $item->Name,
                            'logo' => strlen($item->Logo) > 0 ? $item->Logo : '/uploads/model/'.str_replace('Ë','E',$item->Name).'.png',
                        ];
                    }
                }else{
                    if(file_exists('./uploads/model/'.str_replace('Ë','E',$item->Name).'.png')){
                        $data['manufacturers'][] = [
                            'slug' => url_title($item->Name).'_'.$item->ID_mfa,
                            'ID_mfa' => $item->ID_mfa,
                            'name' => $item->Name,
                            'logo' => strlen($item->Logo) > 0 ? $item->Logo : '/uploads/model/'.str_replace('Ë','E',$item->Name).'.png',
                        ];
                    }
                }
            }
            //$this->output->cache('2628000');
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
}
