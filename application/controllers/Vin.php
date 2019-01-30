<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Vin extends Front_controller{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $data = [];

        $seo = $this->settings_model->get_by_key('seo_vin');

        $this->setTitle(@$seo['title'] ? $seo['title'] : lang('text_h1'));
        $this->setDescription(@$seo['description '] ? $seo['description '] : lang('text_h1'));
        $this->setKeywords(@$seo['keywords'] ? $seo['keywords'] : lang('text_h1'));
        $this->setH1(@$seo['h1'] ? $seo['h1']:lang('text_h1'));
        $this->setSeotext(@$seo['text']);

        $data['h1'] = $this->h1;

        $this->setOg('title',$this->title);
        $this->setOg('description',$this->description);
        $this->setOg('url',current_url());
        
        $this->load->view('header');
        $this->load->view('vin/vin', $data);
        $this->load->view('footer');
    }
}