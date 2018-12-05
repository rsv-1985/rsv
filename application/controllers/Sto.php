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
        $this->load->language('sto_lang');
        $data = [];

        $settings = $this->settings_model->get_by_key('sto_settings');
        $statuses = $this->sto_model->getStatuses();

        $new_status = false;

        if($statuses){
           foreach ($statuses as $st){
               if($st['is_new']){
                   $new_status = $st['id'];
                   break;
               }
           }

           if(!$new_status){
               $new_status = $statuses[0]['id'];
           }
        }

        $this->setTitle($settings['title']);
        $this->setDescription($settings['meta_description']);
        $this->setKeywords($settings['meta_keywords']);
        $this->setH1($settings['h1']);
        $data['h1'] = $this->h1;

        $data['description'] = $settings['description'];
        $data['services'] = $this->sto_model->getServices();

        if ($this->input->post()) {
            $this->form_validation->set_rules('service_id', 'Услуга', 'required');
            $this->form_validation->set_rules('manufacturer', 'Производитель', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('model', 'Модель', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('typ', 'Модификация', 'trim|max_length[255]');
            $this->form_validation->set_rules('vin', 'VIN автомобиля', 'trim|max_length[32]');
            $this->form_validation->set_rules('date', 'Дата', 'required|trim|max_length[32]');
            $this->form_validation->set_rules('time', 'Время', 'required|trim|max_length[32]');
            $this->form_validation->set_rules('name', 'ФИО', 'trim|max_length[255]');
            $this->form_validation->set_rules('phone', 'Телефон', 'required|trim|max_length[32]');
            $this->form_validation->set_rules('carnumber', 'Номер автомобиля', 'trim|max_length[32]');
            $this->form_validation->set_rules('email', 'E-mail', 'max_length[255]|valid_email');
            $this->form_validation->set_rules('comment', 'Комментарий', 'trim|max_length[3000]');
            $this->form_validation->set_rules('cmsautox', 'cmsautox', 'required|trim');

            if ($this->form_validation->run() == true && $this->input->post('cmsautox') == 'true') {
                $this->_save_data($new_status);
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
        $save['service_id'] = $this->input->post('service_id',true);
        $save['manufacturer'] = $this->input->post('manufacturer',true);
        $save['model'] = $this->input->post('model',true);
        $save['vin'] = $this->input->post('vin',true);
        $save['date'] = $this->input->post('date',true).' '.$this->input->post('time',true);
        $save['name'] = $this->input->post('name',true);
        $save['phone'] = $this->input->post('phone',true);
        $save['carnumber'] = $this->input->post('carnumber',true);
        $save['email'] = $this->input->post('email',true);
        $save['comment'] = $this->input->post('comment',true);
        $save['created_at'] = date('Y-m-d H:i:s');
        $save['updated_at'] = date('Y-m-d H:i:s');
        $save['status_id'] = $status;
        $id = $this->sto_model->insert($save);

        //Если есть шаблон смс или email отправлем сообщение
        $status_info = $this->sto_model->getStatus($status);
        $sto_info = $this->sto_model->get($id);

        $this->load->library('sender');
        //SMS
        if($status_info['sms_template'] && $save['phone'] != ''){
            $message = $status_info['sms_template'];

            foreach ($sto_info as $key => $value){
                if($key == 'status_id'){
                    $value = $sto_info['name'];
                }

                if($key == 'service_id'){
                    $value = $this->sto_model->getService($value)['name'];
                }
                $message = str_replace('{'.$key.'}',$value, $message);
            }

            $this->sender->sms($save['phone'], $message);
        }
        //EMAIL
        if($status_info['email_template'] && $save['email'] != ''){
            $message = $status_info['email_template'];

            foreach ($sto_info as $key => $value){
                if($key == 'status_id'){
                    $value = $sto_info['name'];
                }

                if($key == 'service_id'){
                    $value = $this->sto_model->getService($value)['name'];
                }
                $message = str_replace('{'.$key.'}',$value, $message);
            }

            $this->sender->email('СТО', nl2br($message), $save['email'], explode(';', $this->contacts['email']));
        }


        $settings = $this->settings_model->get_by_key('sto_settings');

        //SMS админу
        if(@$settings['telephone_notification']){
            $telephones = explode(';',$settings['telephone_notification']);
            foreach ($telephones as $telephone){
                $this->sender->sms($telephone, 'Новая заявка СТО №'.$id);
            }
        }
        //Email админу
        if(@$settings['email_notification']){
            $emails = explode(';',$settings['email_notification']);
            foreach ($emails as $email){
                $this->sender->email('Новая заявка СТО', 'Новая заявка СТО №'.$id, $email, explode(';', $this->contacts['email']));
            }
        }
    }
}