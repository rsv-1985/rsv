<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Tecdoc_settings extends Admin_controller{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        exit('da');
    }

    public function manufacturer(){
        if($this->input->post()){
            $this->db->where('group_settings','tecdoc_manufacturer');
            $this->db->or_where('key_settings','tecdoc_manufacturer');
            $this->db->delete('settings');

            $save['group_settings'] = 'tecdoc_settings';
            $save['key_settings'] = 'tecdoc_manufacturer';
            $save['value'] = serialize($this->input->post('tecdoc_manufacturer'));
            $this->settings_model->add($save);
        }
        $data['manufacturers'] = $this->tecdoc->getManufacturer();
        $data['tecdoc_manufacturer'] = $this->settings_model->get_by_key('tecdoc_manufacturer');
        $this->load->view('admin/header');
        $this->load->view('admin/tecdoc_settings/tecdoc_manufacturer', $data);
        $this->load->view('admin/footer');
    }

    public function model(){
        if($this->input->post()){
            @file_put_contents('./robots.txt',$this->input->post('robots', true));
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin');
        }
        $data['robots'] = @file_get_contents('./robots.txt');

        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_robots', $data);
        $this->load->view('admin/footer');
    }

    public function type(){
        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_sitemap');
        $this->load->view('admin/footer');
    }

    public function tree(){
        if($this->input->post()){
            $this->db->where('key_settings','tecdoc_tree');
            $this->db->delete('settings');

            $save['group_settings'] = 'tecdoc_settings';
            $save['key_settings'] = 'tecdoc_tree';
            $save['value'] = serialize($this->input->post('tecdoc_tree'));

            $this->settings_model->add($save);

            $this->clear_cache();

            $this->session->set_flashdata('success', lang('text_success'));
            redirect('/autoxadmin/tecdoc_settings/tree');
        }

        $data['settings_tecdoc_tree'] = $this->settings_model->get_by_key('tecdoc_tree');
        $data['trees'] = $this->tecdoc->getTreeFull();


        $this->load->view('admin/header');
        $this->load->view('admin/tecdoc_settings/tecdoc_tree', $data);
        $this->load->view('admin/footer');
    }
}
