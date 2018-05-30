<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Cross extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/cross');
        $this->load->model('cross_model');
        $this->load->model('product_model');
        $this->load->helper('file');
    }

    public function delete_by_filter(){
        $this->cross_model->delete_by_filter();
        exit('Все прошло успешно');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');



        $config['base_url'] = base_url('autoxadmin/cross/index');
        $config['per_page'] = 10;
        $data['crosses'] = $this->cross_model->cross_get_all($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->cross_model->total_rows;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $this->load->view('admin/header');
        $this->load->view('admin/cross/cross', $data);
        $this->load->view('admin/footer');

    }

    public function create(){
        $data = [];

        if($this->input->post()){
            if(empty($_FILES['userfile']['name'])){
                $this->form_validation->set_rules('code', 'Code', 'required|max_length[32]|trim');
                $this->form_validation->set_rules('brand', 'Brand', 'required|max_length[32]|trim');
                $this->form_validation->set_rules('code2', 'Code2', 'required|max_length[32]|trim');
                $this->form_validation->set_rules('brand2', 'Brand2', 'required|max_length[32]|trim');
            }else{
                $this->form_validation->set_rules('userfile', 'File', 'file');
            }

            if ($this->form_validation->run() !== false){

                if(!empty($_FILES['userfile']['name'])){

                    $config['upload_path']          = './uploads/cross/';
                    $config['allowed_types']        = '*';
                    $config['file_ext_tolower']     = true;
                    $config['encrypt_name']         = true;
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('userfile')){
                        $upload_data = $this->upload->data();
                        $file_name = './uploads/cross/' . $upload_data['file_name'];

                        //В зависимсти от типа файла запускаем его обработку
                        switch($upload_data['file_ext']){
                            case '.xls':
                                $this->xls_read($file_name, $this->input->post('xcross'));
                                break;
                            case '.csv':
                                $this->csv_read($file_name, $this->input->post('xcross'));
                                break;
                            default:
                                $this->error = 'Error file type';
                                break;
                        }

                    }else{
                        $this->error = $this->upload->display_errors();
                    }
                }else{
                    $this->save_data();
                }
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/cross/create', $data);
        $this->load->view('admin/footer');
    }

    private function save_data($id = false){
        $save = [];
        $save['code'] = $this->product_model->clear_sku($this->input->post('code', true));
        $save['brand'] = $this->product_model->clear_brand($this->input->post('brand', true));
        $save['code2'] = $this->product_model->clear_sku($this->input->post('code2', true));
        $save['brand2'] = $this->product_model->clear_brand($this->input->post('brand2', true));
        $this->cross_model->insert($save, $id);
        if($this->input->post('xcross')){
            $save = [];
            $save['code'] = $this->product_model->clear_sku($this->input->post('code2', true));
            $save['brand'] = $this->product_model->clear_brand($this->input->post('brand2', true));
            $save['code2'] = $this->product_model->clear_sku($this->input->post('code', true));
            $save['brand2'] = $this->product_model->clear_brand($this->input->post('brand', true));
            $this->cross_model->insert($save, $id);
        }

        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/cross');
    }

    public function delete($id){
        $this->cross_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/cross');
    }

    public function delete_all(){
        $this->cross_model->truncate();
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/cross');
    }

    public function csv_read($file_name = false,$xcross = false){

        if($this->input->get('file_name')){
            $file_name = $this->input->get('file_name', true);
        }

        if($this->input->get('xcross')){
            $xcross = $this->input->get('xcross');
        }

        if (($handle_f = fopen($file_name, "r")) !== false) {
            if (isset($_GET['ftell'])) {
                fseek($handle_f, $_GET['ftell']);
            }
            $i = 0;
            if (isset($_GET['x'])) {
                $x = $_GET['x'];
            } else {
                $x = 0;
            }
            $save = [];
            $save2 = [];
            // построчное считывание и анализ строк из файла
            while (($data_f = fgetcsv($handle_f, 1000, ';')) !== false) {

                $encoding = mb_detect_encoding($data_f[0],mb_detect_order(),true);

                if($encoding != 'UTF-8'){
                    $data_f = array_map(function($text){
                        return iconv('WINDOWS-1251', "UTF-8", $text);
                    },$data_f);
                }

                $code = $this->product_model->clear_sku($data_f[0]);
                $brand = $this->product_model->clear_brand($data_f[1]);
                $code2 = $this->product_model->clear_sku($data_f[2]);
                $brand2 =$this->product_model->clear_brand($data_f[3]);

                if(!$code || !$brand || !$code2 || !$brand2){
                    continue;
                }

                $save[]= [
                    'code' => $code,
                    'brand' => $brand,
                    'code2' => $code2,
                    'brand2' => $brand2,
                ];
                if($xcross){
                    $save2[] = [
                        'code' => $code2,
                        'brand' => $brand2,
                        'code2' => $code,
                        'brand2' => $brand,
                    ];
                }


                if ($i == 2000) {
                    $this->cross_model->insert_batch($save);
                    $q = 0;
                    $save = [];
                    if($xcross){
                        $this->cross_model->insert_batch($save2);
                        $save2 = [];
                    }
                    $url = base_url('autoxadmin/cross/csv_read') . '?file_name='.$file_name.'&xcross='.$xcross;
                    echo('<html>
                    <head>
                    <title>Загрузка</title>
                    </head>
                    <body>
                    Идет загрузка.<br /><a id="go" href=\''.$url.
                        '&x=' . $x . '&ftell=' . ftell($handle_f) .'\'>.</a>
                    <script type="text/javascript">document.getElementById(\'go\').click();</script>
                    </body>
                    </html>');
                    die();
                }
                $x++;
                $i++;
            }
            if (count($save)) {
                $this->cross_model->insert_batch($save);
            }
            if (count($save2)) {
                $this->cross_model->insert_batch($save2);
            }
            delete_files('./uploads/cross/');
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/cross');
            fclose($handle_f);
        }


    }

    public function xls_read($file_name,$xcross){
        //Подключаме бтблиотеку для работы с xls
        error_reporting(E_ALL ^ E_NOTICE);
        require_once APPPATH . 'libraries/excel_reader2.php';

        $excel = new Spreadsheet_Excel_Reader($file_name, false);
        if ($excel->sheets[0]['numRows'] > 0) {
            $save = [];
            $save2 = [];
            $q = 0;
            for ($i = 2; $i <= $excel->sheets[0]['numRows']; $i++) {
                $code = $this->product_model->clear_sku($excel->sheets[0]['cells'][$i][1]);
                $brand = $this->product_model->clear_brand($excel->sheets[0]['cells'][$i][2]);
                $code2 = $this->product_model->clear_sku($excel->sheets[0]['cells'][$i][3]);
                $brand2 =$this->product_model->clear_brand($excel->sheets[0]['cells'][$i][4]);
                $save[]= [
                    'code' => $code,
                    'brand' => $brand,
                    'code2' => $code2,
                    'brand2' => $brand2,
                ];
                if($xcross){
                    $save2[] = [
                        'code' => $code2,
                        'brand' => $brand2,
                        'code2' => $code,
                        'brand2' => $brand,
                    ];
                }
                $q++;
                if ($q > 2000) {
                    $this->cross_model->insert_batch($save);
                    $q = 0;
                    $save = [];
                    if($xcross){
                        $this->cross_model->insert_batch($save2);
                        $save2 = [];
                    }
                }

            }
            if (count($save)) {
                $this->cross_model->insert_batch($save);
            }
            if (count($save2)) {
                $this->cross_model->insert_batch($save2);
            }
            delete_files('./uploads/cross/');
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/cross');

        } else {
            $this->session->set_flashdata('error', 'Error read file');
            redirect('autoxadmin/cross');
        }
    }
}