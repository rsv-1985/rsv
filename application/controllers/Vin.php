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

        $this->title = @$seo['title'] ? $seo['title'] : lang('text_h1');
        $this->description = @$seo['description '] ? $seo['description '] : lang('text_h1');
        $this->keywords = @$seo['keywords'] ? $seo['keywords'] : lang('text_h1');;

        $data['h1'] = @$seo['h1'] ? $seo['h1']:lang('text_h1');
        $data['text']  = @$seo['text'];
        
        $this->load->view('header');
        $this->load->view('vin/vin', $data);
        $this->load->view('footer');
    }
}