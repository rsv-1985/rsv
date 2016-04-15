<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->language('admin/import');
        $this->load->helper('file');
        $this->load->model('product_model');
        $this->load->model('import_model');
        $this->load->model('synonym_model');
        $this->load->model('supplier_model');
        $this->load->model('pricing_model');
        $this->load->model('sample_model');
        $this->load->model('currency_model');
        $this->load->model('category_model');
    }

    public function index(){
        $data = [];
        if($this->import_model->count_all() > 0){
            redirect('autoxadmin/import/tmptable');
        }
        if($this->input->post()){
            $this->form_validation->set_rules('supplier_id', lang('text_supplier'), 'integer|required');
            if($this->input->post('sample_id')){
                $this->form_validation->set_rules('sample_id', lang('text_sample'), 'integer|required');
            }else{
                $this->form_validation->set_rules('sample_name', 'sample_name', 'trim|max_length[32]');
                $this->form_validation->set_rules('sample[sku]', 'sku', 'integer|required');
                $this->form_validation->set_rules('sample[brand]', 'brand', 'integer|required');
                $this->form_validation->set_rules('sample[quantity]', 'quantity', 'integer|required');
                $this->form_validation->set_rules('sample[currency_id]', 'currency', 'integer|required');
                $this->form_validation->set_rules('sample[delivery_price]', 'delivery_price', 'integer|required');
            }
            if ($this->form_validation->run() !== false){
                if($this->input->post('sample_save')){
                    $save = [];
                    $save['supplier_id'] = (int)$this->input->post('supplier_id', true);
                    $save['name'] = $this->input->post('sample_name', true);
                    $save['value'] = serialize($this->input->post('sample'));
                    $this->sample_model->insert($save);
                }

                if($this->input->post('sample_id')){
                    $sample = unserialize($this->sample_model->get((int)$this->input->post('sample_id'))['value']);
                }else{
                    $sample = $this->input->post('sample');
                }

                //Загружаем прайс
                $config['upload_path']          = './uploads/import/';
                $config['allowed_types']        = 'xls|csv';
                $config['file_ext_tolower']     = true;
                $config['encrypt_name']         = true;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('filename')){
                    $upload_data = $this->upload->data();
                    $file_name = './uploads/import/' . $upload_data['file_name'];
                    //В зависимсти от типа файла запускаем его обработку
                    switch($upload_data['file_ext']){
                        case '.xls':
                            $this->xls_read($file_name, $sample, (int)$this->input->post('supplier_id', true));
                            break;
                        case '.csv':
                            $this->csv_read($file_name, $sample, (int)$this->input->post('supplier_id', true));
                            break;
                        default:
                            $this->error = 'Error file type';
                            break;
                    }
                }else{
                    $this->error = $this->upload->display_errors();
                }
            }else{
                $this->error = validation_errors();
            }
        }
        $data['suppliers'] = $this->supplier_model->get_all();
        $data['currency'] = $this->currency_model->get_all();
        $data['category'] = $this->category_model->get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/import/import', $data);
        $this->load->view('admin/footer');
    }

    public function tmptable(){
        $data = [];
        $data['total'] = $this->import_model->count_all();

        if($data['total'] == 0){
            redirect('autoxadmin/import');
        }

        $data['importtmp'] = $this->import_model->get_all(25);
        $data['supplier'] = $this->supplier_model->get_all();

        $this->load->view('admin/header');
        $this->load->view('admin/import/tmptable', $data);
        $this->load->view('admin/footer');
    }

    public function cancel(){
        $this->load->helper('file');
        delete_files('./uploads/import/');
        $this->import_model->truncate();
        $this->session->set_flashdata('success', lang('text_success_cancel'));
        redirect('autoxadmin/import');
    }

    public function add($id = 0){
        if($this->input->get('supplier_id') && $id == 0){
            $supplier_id = (int)$this->input->get('supplier_id');
            switch($this->input->get('settings')){
                case 2:
                    $this->db->where('supplier_id',$supplier_id )->delete('product');
                    break;
                case 1:
                    if($this->input->get('disable')){
                        $this->db->where('supplier_id',$supplier_id)->update('product',['status' => false]);
                    }
                    break;
            }
        }
        $products = $this->import_model->import_get_all($id);

        if($products){
            foreach($products as &$product){
                $product['created_at'] = date("Y-m-d H:i:s");
                $product['updated_at'] = date("Y-m-d H:i:s");
                $product['price'] = $product['delivery_price'];
                $product['slug'] = url_title($product['name'].' '.$product['sku'].' '.$product['brand'].' '.$product['supplier_id'], 'dash', true);
            }


            $this->product_model->insert_on_duplicate_key($products);

            echo('<html>
                    <head>
                    <title>Import...</title>
                    </head>
                    <body>
                    Import...<br /><a id="go" href="'.base_url('autoxadmin/import/add').'/'.$product['id'].'?supplier_id='.$this->input->get('supplier_id').'"\'>.</a>
                    <script type="text/javascript">document.getElementById(\'go\').click();</script>
                    </body>
                    </html>');
            die();
        }else{
            $this->import_model->truncate();
            delete_files('./uploads/import/');
            $this->product_model->set_price($this->input->get('supplier_id'));
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/import');
        }
    }

    public function csv_read($file_name = false, $sample = false, $supplier_id = false){
        $synonyms = $this->synonym_model->get_synonyms();

        if($this->input->get('params')){
            $params = unserialize($this->input->get('params'));
        }else{
            $params = array(
                'file_name' => $file_name,
                'sample' => $sample,
                'supplier_id' => $supplier_id,
            );
        }

        if (($handle_f = fopen($params['file_name'], "r")) !== false) {
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
            // построчное считывание и анализ строк из файла
            while (($data_f = fgetcsv($handle_f, 1000, ';')) !== false) {
                if(isset($data_f[$params['sample']['sku'] - 1])){
                    $sku = $this->product_model->clear_sku($data_f[$params['sample']['sku'] - 1]);
                } else {
                    $sku = '';
                }
                if(isset($data_f[$params['sample']['brand'] - 1])){
                    $brand = $this->product_model->clear_brand($data_f[$params['sample']['brand'] - 1], $synonyms);
                } else {
                    $brand = '';
                }

                if(isset($data_f[$params['sample']['name'] - 1])){
                    $name = trim($data_f[$params['sample']['name'] - 1]);
                } else {
                    $name = '';
                }

                if(isset($data_f[$params['sample']['quantity'] - 1])){
                    $quantity = $this->product_model->clear_quan($data_f[$params['sample']['quantity'] - 1]);
                } else {
                    $quantity = 0;
                }

                if(isset($data_f[$params['sample']['delivery_price'] - 1])){
                    $delivery_price = $this->product_model->clear_price($data_f[$params['sample']['delivery_price'] - 1]);
                } else {
                    $delivery_price = 0;
                }

                if(isset($data_f[$params['sample']['saleprice'] - 1])){
                    $saleprice = $this->product_model->clear_price($data_f[$params['sample']['saleprice'] - 1]);
                } else {
                    $saleprice = 0;
                }


                if(isset($data_f[$params['sample']['description'] - 1])){
                    $description = trim($data_f[$params['sample']['description'] - 1]);
                } else {
                    $description = '';
                }

                if(isset($data_f[$params['sample']['excerpt'] - 1])){
                    $excerpt = trim($data_f[$params['sample']['excerpt'] - 1]);
                } else {
                    $excerpt = '';
                }

                if(isset($data_f[$params['sample']['term'] - 1])){
                    $term = (int)$data_f[$params['sample']['term'] - 1];
                } else {
                    $term = $params['sample']['default_term'];
                }

                if(isset($data_f[$params['sample']['category'] - 1])){
                    $category_id = (int)$data_f[$params['sample']['category'] - 1];
                } else {
                    $category_id = $params['sample']['default_category_id'];
                }

                if(isset($data_f[$params['sample']['image'] - 1])){
                    $image = (int)$data_f[$params['sample']['image'] - 1];
                } else {
                    $image = '';
                }

                $currency_id = $params['sample']['currency_id'];

                $save[]= [
                    'sku' => $sku,
                    'brand' => $brand,
                    'name' => $name,
                    'description' => $description,
                    'excerpt' => $excerpt,
                    'currency_id' => $currency_id,
                    'delivery_price' => $delivery_price,
                    'saleprice' => $saleprice,
                    'quantity' => $quantity,
                    'supplier_id' => $params['supplier_id'],
                    'term' => $term,
                    'category_id' => $category_id,
                    'image' => $image
                ];

                if ($i == 2000) {
                    $this->import_model->insert_batch($save);
                    echo('<html>
                    <head>
                    <title>Загрузка</title>
                    </head>
                    <body>
                    Идет загрузка.<br /><a id="go" href=\''.base_url('autoxadmin/import/csv_read') . '?params=' . serialize($params).
                        '&x=' . $x . '&ftell=' . ftell($handle_f) .'\'>.</a>
                    <script type="text/javascript">document.getElementById(\'go\').click();</script>
                    </body>
                    </html>');
                    die();
                }
                $x++;
                $i++;
            }
            if(count($save)){
                $this->import_model->insert_batch($save);
                $this->finish($params['supplier_id']);
            }
            fclose($handle_f);
        }
    }

    private function finish($supplier_id){
        //Удаляем позиции в которых цена 0 или наличие 0
        $this->import_model->clear_importtmp();
        //Обновляем дату последнего обновления у поставщика
        $this->supplier_model->insert(['updated_at' => date("Y-m-d H:i:s")], $supplier_id);
        if($this->import_model->count_all() > 0){
            $this->session->set_flashdata('success', lang('text_success_import'));
            redirect('autoxadmin/import/tmptable?supplier_id='.$supplier_id);
        }else{
            $this->session->set_flashdata('error', lang('text_error_import'));
            redirect('autoxadmin/import');
        }
    }

    public function xls_read($file_name, $sample, $supplier_id){
        //Подключаме бтблиотеку для работы с xls
        error_reporting(E_ALL ^ E_NOTICE);
        require_once APPPATH . 'libraries/excel_reader2.php';
        $synonyms = $this->synonym_model->get_synonyms();

        $excel = new Spreadsheet_Excel_Reader($file_name, false);
        if ($excel->sheets[0]['numRows'] > 0) {
            $save = [];
            $q = 0;
            for ($i = 1; $i <= $excel->sheets[0]['numRows']; $i++) {
                $sku = $this->product_model->clear_sku($excel->sheets[0]['cells'][$i][$sample['sku']]);
                $brand = $this->product_model->clear_brand($excel->sheets[0]['cells'][$i][$sample['brand']], $synonyms);
                $name = trim($excel->sheets[0]['cells'][$i][$sample['name']]);
                $quantity = $this->product_model->clear_quan($excel->sheets[0]['cells'][$i][$sample['quantity']]);
                $delivery_price = $this->product_model->clear_price($excel->sheets[0]['cells'][$i][$sample['delivery_price']]);
                $saleprice = $this->product_model->clear_price($excel->sheets[0]['cells'][$i][$sample['saleprice']]);
                if(!empty($sample['term'])){
                    $term = (int)$excel->sheets[0]['cells'][$i][$sample['term']];
                } else {
                    $term = $sample['default_term'];
                }
                if(!empty($sample['category'])){
                    $category_id = (int)$excel->sheets[0]['cells'][$i][$sample['category']];
                    if(empty($category_id)){
                        $category_id = $sample['default_category_id'];
                    }
                } else {
                    $category_id = $sample['default_category_id'];
                }
                $description = trim($excel->sheets[0]['cells'][$i][$sample['description']]);
                $excerpt = trim($excel->sheets[0]['cells'][$i][$sample['excerpt']]);
                $image = trim($excel->sheets[0]['cells'][$i][$sample['image']]);
                $currency_id = $sample['currency_id'];

                $save[]= [
                    'sku' => $sku,
                    'brand' => $brand,
                    'name' => $name,
                    'description' => $description,
                    'excerpt' => $excerpt,
                    'currency_id' => $currency_id,
                    'delivery_price' => $delivery_price,
                    'saleprice' => $saleprice,
                    'quantity' => $quantity,
                    'supplier_id' => $supplier_id,
                    'term' => $term,
                    'category_id' => $category_id,
                    'image' => $image
                ];
                $q++;
                if ($q > 2000) {
                    $this->import_model->insert_batch($save);
                    $q = 0;
                    $save = [];
                }

            }
            if (count($save)) {
                $this->import_model->insert_batch($save);
            }

        } else {
            $this->session->set_flashdata('error', 'Error read file');
            redirect('autoxadmin/import');
        }

        $this->finish($supplier_id);
    }

}