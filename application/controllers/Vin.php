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

        $this->title = lang('text_h1');
        $this->description = lang('text_h1');
        $this->keywords = lang('text_h1');;

        $data['h1'] = lang('text_h1');

        $this->load->view('header');
        $this->load->view('vin/vin', $data);
        $this->load->view('footer');
    }
}