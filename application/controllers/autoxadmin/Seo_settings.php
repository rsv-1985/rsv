<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Seo_settings extends Admin_controller{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        exit('da');
    }

    public function product(){
        if($this->input->post()){
            $this->db->where('group_settings','seo_product');
            $this->db->or_where('key_settings','seo_product');
            $this->db->delete('settings');

            $save['group_settings'] = 'seo_product';
            $save['key_settings'] = 'seo_product';
            $save['value'] = serialize($this->input->post('seo_product'));
            $this->settings_model->add($save);

            $this->db->where('key_settings', 'seo_url_template');
            $this->db->delete('settings');

            $save['group_settings'] = 'seo_product';
            $save['key_settings'] = 'seo_url_template';
            $save['value'] = serialize($this->input->post('seo_url_template'));
            $this->settings_model->add($save);
        }
        $data['seo_product'] = $this->settings_model->get_by_key('seo_product');

        $data['seo_url_template'] = $this->settings_model->get_by_key('seo_url_template');

        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_product', $data);
        $this->load->view('admin/footer');
    }

    public function robots(){
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

    public function sitemap(){
        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_sitemap');
        $this->load->view('admin/footer');
    }

    public function tecdoc_manufacturer(){
        if($this->input->post()){
            $this->db->where('key_settings','seo_tecdoc_manufacturer');
            $this->db->delete('settings');

            $save['group_settings'] = 'seo_tecdoc';
            $save['key_settings'] = 'seo_tecdoc_manufacturer';
            $save['value'] = serialize($this->input->post('seo_tecdoc_manufacturer'));
            $this->settings_model->add($save);

            $this->clear_cache();

            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin');
        }
        $data['seo_tecdoc_manufacturer'] = $this->settings_model->get_by_key('seo_tecdoc_manufacturer');

        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_tecdoc_manufacturer', $data);
        $this->load->view('admin/footer');
    }

    public function tecdoc_model(){
        if($this->input->post()){
            $this->db->where('key_settings','seo_tecdoc_model');
            $this->db->delete('settings');

            $save['group_settings'] = 'seo_tecdoc';
            $save['key_settings'] = 'seo_tecdoc_model';
            $save['value'] = serialize($this->input->post('seo_tecdoc_model'));
            $this->settings_model->add($save);

            $this->clear_cache();

            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin');
        }
        $data['seo_tecdoc_model'] = $this->settings_model->get_by_key('seo_tecdoc_model');

        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_tecdoc_model', $data);
        $this->load->view('admin/footer');
    }

    public function tecdoc_type(){
        if($this->input->post()){
            $this->db->where('key_settings','seo_tecdoc_type');
            $this->db->delete('settings');

            $save['group_settings'] = 'seo_tecdoc';
            $save['key_settings'] = 'seo_tecdoc_type';
            $save['value'] = serialize($this->input->post('seo_tecdoc_type'));
            $this->settings_model->add($save);

            $this->clear_cache();

            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin');
        }
        $data['seo_tecdoc_type'] = $this->settings_model->get_by_key('seo_tecdoc_type');

        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_tecdoc_type', $data);
        $this->load->view('admin/footer');
    }

    public function tecdoc_tree(){
        if($this->input->post()){
            $this->db->where('key_settings','seo_tecdoc_tree');
            $this->db->delete('settings');

            $save['group_settings'] = 'seo_tecdoc';
            $save['key_settings'] = 'seo_tecdoc_tree';
            $save['value'] = serialize($this->input->post('seo_tecdoc_tree'));
            $this->settings_model->add($save);

            $this->clear_cache();

            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin');
        }
        $data['seo_tecdoc_tree'] = $this->settings_model->get_by_key('seo_tecdoc_tree');

        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_tecdoc_tree', $data);
        $this->load->view('admin/footer');
    }

    public function hook(){
        if($this->input->get('delete')){
            $this->db->where('key_settings',$this->input->get('delete',true));
            $this->db->delete('settings');
            $this->clear_cache();
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('/autoxadmin/seo_settings/hook');
        }
        if($this->input->get('edit')){
            $data['hook'] = $this->settings_model->get_by_key($this->input->get('edit',true));
        }
        if($this->input->post('hook')){
            $this->db->where('key_settings',$this->input->post('url',true));
            $this->db->delete('settings');

            $save['group_settings'] = 'seo_hook';
            $save['key_settings'] = $this->input->post('url',true);
            $save['value'] = serialize($this->input->post('hook'));

            $this->settings_model->add($save);

            $this->clear_cache();

            $this->session->set_flashdata('success', lang('text_success'));
            redirect('/autoxadmin/seo_settings/hook');
        }

        $data['seo_hooks'] = $this->settings_model->get_by_group('seo_hook');

        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_hook', $data);
        $this->load->view('admin/footer');
    }

    public function brand(){
        if($this->input->post()){
            $this->db->where('group_settings','seo_brand');
            $this->db->or_where('key_settings','seo_brand');
            $this->db->delete('settings');

            $save['group_settings'] = 'seo_brand';
            $save['key_settings'] = 'seo_brand';
            $save['value'] = serialize($this->input->post('seo_brand'));
            $this->settings_model->add($save);
        }
        $data['seo_brand'] = $this->settings_model->get_by_key('seo_brand');

        $this->load->view('admin/header');
        $this->load->view('admin/seo_settings/seo_brand', $data);
        $this->load->view('admin/footer');
    }

    public function get_hook(){
        $url = $this->input->post('url',true);
        $hook = $this->settings_model->get_by_key($url);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($hook));
    }
}
