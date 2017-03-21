<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sto extends Admin_controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sto_model');
    }

    public function index(){
        $settings = $this->settings_model->get_by_key('sto_settings');
        $data['statuses'] = [];

        if(isset($settings['status'])){
            foreach (explode(PHP_EOL,$settings['status']) as $status){
                $status = explode('#',$status);
                $data['statuses'][@$status[0]] = [
                    'color' => '#'.@$status[1]
                ];
            }
        }

        $prefs = array(
            'start_day'    => 'monday',
            'month_type'   => 'long',
            'day_type' => 'abr',
            'show_next_prev' => TRUE,
            'next_prev_url' => '/autoxadmin/sto/index',
            'show_other_days' => TRUE,
            'template' => [
                'table_open'  => '<table class="calendar">',
                'heading_row_start' => '<tr class="heading">',
                'week_row_start' => '<tr class="week">',
                'cal_row_start' => '<tr class="cal">',
                'cal_cell_content' =>  '<p class="day">{day}</p>{content}',
                'cal_cell_content_today' =>  '<p class="day today">{day}</p>{content}',
            ]
        );

        $this->load->library('calendar', $prefs);

        $year = $this->uri->segment(4) ? $this->uri->segment(4) : date('Y');
        $month = $this->uri->segment(5) ? $this->uri->segment(5) : date('m');
        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year); // 31
        $records = [];
        for($i = 1; $i <= $number; $i++){
            $date = str_pad($i,2,'0',STR_PAD_LEFT).'.'.$month.'.'.$year;
            $sto = $this->sto_model->get_sto($date);
            if($sto){
                $text = '';
                foreach ($sto as $item){
                    $text .= '<hr><a style="color: '.@$data['statuses'][$item['status']]['color'].'" href="/autoxadmin/sto/edit/'.$item['id'].'"><b>'.$item['time'].'</b> '.$item['service'].'</a>';
                }
                $records[$i] = $text;
            }
        }



        $data['calendar'] = $this->calendar->generate($year, $month,$records);

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

    public function settings(){
        $this->load->language('admin/sto');
        if($this->input->post()){
            $this->db->where('group_settings','sto_settings');
            $this->db->delete('settings');
            $save['group_settings'] = 'sto_settings';
            $save['key_settings'] = 'sto_settings';
            $save['value'] = serialize($this->input->post());
            $this->settings_model->add($save);
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/sto/settings');
        }
        $data['settings'] = $this->settings_model->get_by_key('sto_settings');
        $this->load->view('admin/header');
        $this->load->view('admin/sto/settings', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->sto_model->delete($id);
        $this->session->set_flashdata('success', 'Запись удалена');
        redirect('autoxadmin/sto');
    }

    public function edit($id){
        $settings = $this->settings_model->get_by_key('sto_settings');

        $data['services'] = explode(PHP_EOL, $settings['services']);
        $data['statuses'] = [
           'Новый','Подтвержден'
        ];

        if(isset($settings['status'])){
            $data['statuses'] = [];
            foreach (explode(PHP_EOL,$settings['status']) as $status){
                $status = explode('#',$status);
                $data['statuses'][] = @$status[0];
            }
        }
        $data['record']  = $this->sto_model->get($id);

        if($this->input->post()){
            $this->_save_data($this->input->post('status'),$id);
            $this->session->set_flashdata('success', 'Запись обновлена');
            redirect('autoxadmin/sto');
        }
        $this->load->view('admin/header');
        $this->load->view('admin/sto/edit', $data);
        $this->load->view('admin/footer');
    }

    private function _save_data($status,$id = false){
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
        $this->sto_model->insert($save, $id);
    }
}