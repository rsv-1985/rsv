<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/settings');
    }

    public function index(){

        $data = [];
        $data['settings'] = $this->settings_model->get_by_group('company_settings');
        $data['tecdoc_manufacturer'] = $this->tecdoc->getManufacturer();
        $data['robots'] = @file_get_contents('./robots.txt');

        if($this->input->post()){
            $this->form_validation->set_rules('settings[main_settings][name]', lang('text_settings_main_name'), 'required');
            $this->form_validation->set_rules('settings[main_settings][description]', lang('text_settings_main_description'), 'max_length[3000]');
            $this->form_validation->set_rules('settings[main_settings][meta_description]', lang('text_settings_main_meta_description'), 'max_length[3000]');
            $this->form_validation->set_rules('settings[main_settings][meta_keywords]', lang('text_settings_main_meta_keywords'), 'max_length[3000]');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/settings/edit', $data);
        $this->load->view('admin/footer');
    }


    private function save_data(){
        $this->db->where('group_settings','company_settings');
        $this->db->delete('settings');
        $save = [];
        foreach($this->input->post('settings') as $key => $value){
            $save['group_settings'] = 'company_settings';
            $save['key_settings'] = $key;
            $save['value'] = serialize($value);
            $this->settings_model->add($save);
        }
        @file_put_contents('./robots.txt',$this->input->post('robots', true));
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/settings');
    }
}