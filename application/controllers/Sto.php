<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sto extends Front_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sto_model');
    }

    public function index()
    {
        $data = [];

        $settings = $this->settings_model->get_by_key('sto_settings');

        $status = 'Новый2';
        if(isset($settings['status'])){
            $statuses = explode(PHP_EOL,$settings['status']);
            if(count($statuses)){
                $status = explode('#',$statuses[0])[0];
            }
        }

        $this->title = $settings['title'];
        $this->description = $settings['meta_description'];
        $this->keywords = $settings['meta_keywords'];

        $data['h1'] = $settings['h1'];
        $data['description'] = $settings['description'];
        $data['services'] = explode(PHP_EOL, $settings['services']);
        $data['time_morning'] = explode(PHP_EOL, $settings['time_morning']);
        $data['time_afternoon'] = explode(PHP_EOL, $settings['time_afternoon']);

        $manufacturers = $this->tecdoc->getManufacturer();
        if ($manufacturers) {
            $settings_tecdoc_manufacturer = $this->settings_model->get_by_key('tecdoc_manufacturer');
            $array_manuf = [];
            foreach ($manufacturers as $item) {
                if ($settings_tecdoc_manufacturer) {
                    if (isset($settings_tecdoc_manufacturer[$item->ID_mfa])) {
                        $array_manuf[] = [
                            'slug' => url_title($item->Name) . '_' . $item->ID_mfa,
                            'ID_mfa' => $item->ID_mfa,
                            'name' => $item->Name,
                            'logo' => strlen($item->Logo) > 0 ? $item->Logo : '/uploads/model/' . str_replace('Ë', 'E', $item->Name) . '.png',
                        ];
                    }
                } else {
                    if (file_exists('./uploads/model/' . str_replace('Ë', 'E', $item->Name) . '.png')) {
                        $array_manuf[] = [
                            'slug' => url_title($item->Name) . '_' . $item->ID_mfa,
                            'ID_mfa' => $item->ID_mfa,
                            'name' => $item->Name,
                            'logo' => strlen($item->Logo) > 0 ? $item->Logo : '/uploads/model/' . str_replace('Ë', 'E', $item->Name) . '.png',
                        ];
                    }
                }
            }
            $data['manufacturers'] = $array_manuf;
        }


        if ($this->input->post()) {
            $this->form_validation->set_rules('service', 'Услуга', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('manufacturer', 'Производитель', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('model', 'Модель', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('typ', 'Модификация', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('vin', 'VIN автомобиля', 'trim|max_length[32]');
            $this->form_validation->set_rules('date', 'Дата', 'required|trim|max_length[32]');
            $this->form_validation->set_rules('time', 'Время', 'required|trim|max_length[32]');
            $this->form_validation->set_rules('name', 'ФИО', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('phone', 'Телефон', 'required|trim|max_length[32]');
            $this->form_validation->set_rules('carnumber', 'Номер автомобиля', 'required|trim|max_length[32]');
            $this->form_validation->set_rules('email', 'E-mail', 'required|trim|max_length[255]|valid_email');
            $this->form_validation->set_rules('comment', 'Комментарий', 'trim|max_length[3000]');
            $this->form_validation->set_rules('cmsautox', 'cmsautox', 'required|trim');
            if ($this->form_validation->run() == true && $this->input->post('cmsautox') == 'true') {
                $this->_save_data($status);
                $this->session->set_flashdata('success', 'Ваша заявка отправлена');
                redirect('/sto');
            } else {
                $this->error = validation_errors();
            }
        }

        $this->load->view('header');
        $this->load->view('sto/sto', $data);
        $this->load->view('footer');
    }

    private function _save_data($status){
        $save = [];
        $save['service'] = $this->input->post('service',true);
        $save['manufacturer'] = $this->input->post('manufacturer',true);
        $save['model'] = $this->input->post('model',true);
        $save['typ'] = $this->input->post('typ',true);
        $save['vin'] = $this->input->post('vin',true);
        $save['date'] = $this->input->post('date',true);
        $save['time'] = $this->input->post('time',true);
        $save['name'] = $this->input->post('name',true);
        $save['phone'] = $this->input->post('phone',true);
        $save['carnumber'] = $this->input->post('carnumber',true);
        $save['email'] = $this->input->post('email',true);
        $save['comment'] = $this->input->post('comment',true);
        $save['created_at'] = date('Y-m-d H:i:s');
        $save['updated_at'] = date('Y-m-d H:i:s');
        $save['status'] = $status;
        $this->sto_model->insert($save);
    }
}