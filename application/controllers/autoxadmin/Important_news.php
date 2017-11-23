<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Important_news extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        if($this->input->post()){
            $this->db->where('group_settings','important_news_settings');
            $this->db->delete('settings');

            $save['group_settings'] = 'important_news_settings';
            $save['key_settings'] = 'important_news';
            $save['value'] = serialize($this->input->post());
            $this->settings_model->add($save);
        }
        $data['important_news'] = $this->settings_model->get_by_key('important_news');

        $this->load->view('admin/header');
        $this->load->view('admin/important_news/important_news', $data);
        $this->load->view('admin/footer');
    }
}