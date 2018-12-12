<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sto extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sto_model');
        $this->load->library('sender');
    }

    public function index()
    {
        $settings = $this->settings_model->get_by_key('sto_settings');
        $data['statuses'] = $this->sto_model->getStatuses();

        $prefs = array(
            'start_day' => 'monday',
            'month_type' => 'long',
            'day_type' => 'abr',
            'show_next_prev' => TRUE,
            'next_prev_url' => '/autoxadmin/sto/index',
            'show_other_days' => TRUE,
            'template' => [
                'table_open' => '<table class="calendar">',
                'heading_row_start' => '<tr class="heading">',
                'week_row_start' => '<tr class="week">',
                'cal_row_start' => '<tr class="cal">',
                'cal_cell_content' => '<p class="day">{day}</p>{content}',
                'cal_cell_content_today' => '<p class="day today">{day}</p>{content}',
            ]
        );

        $this->load->library('calendar', $prefs);

        $year = $this->uri->segment(4) ? $this->uri->segment(4) : date('Y');
        $month = $this->uri->segment(5) ? $this->uri->segment(5) : date('m');
        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year); // 31
        $records = [];
        for ($i = 1; $i <= $number; $i++) {
            $date = $year . '-' . $month . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

            $sto = $this->sto_model->get_sto($date);
            if ($sto) {
                $text = '';
                foreach ($sto as $item) {
                    $text .= '<hr><a style="color: ' . @$data['statuses'][$item['status_id']]['color'] . '" href="/autoxadmin/sto/edit/' . $item['id'] . '"><b>' . date('H:i', strtotime($item['date'])) . '</b> ' . $item['service'] . '</a>';
                }
                $records[$i] = $text;
            }
        }


        $data['calendar'] = $this->calendar->generate($year, $month, $records);

        $this->load->library('pagination');
        $config['base_url'] = base_url('autoxadmin/sto/index');
        $config['total_rows'] = $this->sto_model->count_all();
        $config['per_page'] = 20;
        $config['page_query_string'] = TRUE;
        //$config['enable_query_strings'] = TRUE;
        $this->pagination->initialize($config);

        $data['records'] = $this->sto_model->sto_get_all($config['per_page'], $this->input->get('per_page'));

        $this->load->view('admin/header');
        $this->load->view('admin/sto/sto', $data);
        $this->load->view('admin/footer');
    }

    public function create()
    {
        if ($this->input->post()) {
            $this->_save_data($this->input->post('status_id'));
            $this->session->set_flashdata('success', 'Запись добавлена');
            redirect('autoxadmin/sto');
        }

        $data['services'] = $this->sto_model->getServices();
        $data['statuses'] = $this->sto_model->getStatuses();

        $this->load->view('admin/header');
        $this->load->view('admin/sto/create', $data);
        $this->load->view('admin/footer');
    }

    public function settings()
    {
        $this->load->language('admin/sto');
        if ($this->input->post()) {
            $this->db->where('group_settings', 'sto_settings');
            $this->db->delete('settings');
            $save['group_settings'] = 'sto_settings';
            $save['key_settings'] = 'sto_settings';
            $save['value'] = serialize($this->input->post());
            $this->settings_model->add($save);
            $this->session->set_flashdata('success', 'Настройки сохранены');
            redirect('autoxadmin/sto/settings');
        }
        $data['settings'] = $this->settings_model->get_by_key('sto_settings');
        $data['services'] = $this->sto_model->getServices();
        $data['statuses'] = $this->sto_model->getStatuses();
        $this->load->view('admin/header');
        $this->load->view('admin/sto/settings', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        $this->sto_model->delete($id);
        $this->session->set_flashdata('success', 'Запись удалена');
        redirect('autoxadmin/sto');
    }

    public function edit($id)
    {
        $settings = $this->settings_model->get_by_key('sto_settings');

        $data['services'] = $this->sto_model->getServices();
        $data['statuses'] = $this->sto_model->getStatuses();


        $data['record'] = $this->sto_model->get($id);

        if ($this->input->post()) {
            $this->_save_data($this->input->post('status_id'), $id);
            $this->session->set_flashdata('success', 'Запись обновлена');
            redirect('autoxadmin/sto/edit/' . $id);
        }
        $this->load->view('admin/header');
        $this->load->view('admin/sto/edit', $data);
        $this->load->view('admin/footer');
    }

    private function _save_data($status, $id = false)
    {
        $new_status = false;

        if ($id) {
            $sto_info = $this->sto_model->get($id);
            if ($sto_info['status_id'] != $status) {
                $new_status = true;
            }
        }else{
            $new_status = true;
        }

        $save = [];
        $save['service_id'] = (int)$this->input->post('service_id', true);
        $save['manufacturer'] = $this->input->post('manufacturer', true);
        $save['model'] = $this->input->post('model', true);
        $save['vin'] = $this->input->post('vin', true);
        $save['date'] = $this->input->post('date', true) . ' ' . $this->input->post('time', true);
        $save['name'] = $this->input->post('name', true);
        $save['phone'] = $this->input->post('phone', true);
        $save['carnumber'] = $this->input->post('carnumber', true);
        $save['email'] = $this->input->post('email', true);
        $save['comment'] = $this->input->post('comment', true);
        $save['created_at'] = date('Y-m-d H:i:s');
        $save['updated_at'] = date('Y-m-d H:i:s');
        $save['status_id'] = $status;
        $this->sto_model->insert($save, $id);


        $contacts = $this->settings_model->get_by_key('contact_settings');

        //Если был добавлен комментарий и стоят галочки отправки смс или email
        if ($this->input->post('message')) {
            $message = $this->input->post('message', true);


            if ($save['email'] != '' && (bool)$this->input->post('send_email')) {
                $this->sender->email('СТО', $message, $save['email'], explode(';', $contacts['email']));
            }

            if ($save['phone'] != '' && (bool)$this->input->post('send_sms')) {
                $this->sender->sms($save['phone'], $message);
            }
        }

        //Если был изменен статус и в статусе есть шаблоны смс или email
        if($new_status){

            $status_info = $this->sto_model->getStatus($status);

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

                $this->sender->email('СТО', nl2br($message), $save['email'], explode(';', $contacts['email']));
            }

        }
    }

    public function service()
    {
        $this->form_validation->set_rules('name', 'Название', 'required|max_length[255]');
        if ($this->form_validation->run() !== FALSE) {
            $save['name'] = $this->input->post('name', true);
            $save['sort_order'] = (int)$this->input->post('sort_order');
            $service_id = (int)$this->input->post('id');
            $this->sto_model->addService($save, $service_id);
            $this->session->set_flashdata('success', 'Услуга добавлена');
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }

        redirect('/autoxadmin/sto/settings');
    }

    public function delete_service($id)
    {
        $this->sto_model->deleteService($id);
    }

    public function get_service($id)
    {
        $service_info = $this->sto_model->getService($id);
        $this->output
            ->set_content_type('application/html')
            ->set_output(json_encode($service_info));
    }


    public function status()
    {
        $this->form_validation->set_rules('name', 'Название', 'required|max_length[255]');
        if ($this->form_validation->run() !== FALSE) {
            $save['name'] = $this->input->post('name', true);
            $save['color'] = $this->input->post('color', true);
            $save['sms_template'] = $this->input->post('sms_template', true);
            $save['email_template'] = $this->input->post('email_template', true);
            $save['is_new'] = (bool)$this->input->post('is_new', true);

            $status_id = (int)$this->input->post('id');

            $this->sto_model->addStatus($save, $status_id);
            $this->session->set_flashdata('success', 'Статус добавлен');
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }

        redirect('/autoxadmin/sto/settings');
    }

    public function delete_status($id)
    {
        $this->sto_model->deleteStatus($id);
    }

    public function get_status($id)
    {
        $status_info = $this->sto_model->getStatus($id);
        $this->output
            ->set_content_type('application/html')
            ->set_output(json_encode($status_info));
    }
}