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
        $this->load->model('product_attribute_model');
        $this->load->model('import_model');
        $this->load->model('synonym_model');
        $this->load->model('synonym_name_model');
        $this->load->model('supplier_model');
        $this->load->model('pricing_model');
        $this->load->model('sample_model');
        $this->load->model('currency_model');
        $this->load->model('category_model');
    }

    private function detect_delimeter($file)
    {
        // use php's built in file parser class for validating the csv or txt file
        $file = new SplFileObject($file);

        // array of predefined delimiters. Add any more delimiters if you wish
        $delimiters = array(';', ',', '|', ':', '#');

        // store all the occurences of each delimiter in an associative array
        $number_of_delimiter_occurences = array();

        $results = array();

        $i = 0; // using 'i' for counting the number of actual row parsed
        while ($file->valid() && $i <= 3000) {

            $line = $file->fgets();

            foreach ($delimiters as $idx => $delimiter) {

                $regExp = '/[' . $delimiter . ']/';
                $fields = preg_split($regExp, $line);

                // construct the array with all the keys as the delimiters
                // and the values as the number of delimiter occurences
                $number_of_delimiter_occurences[$delimiter] = count($fields);

            }

            $i++;
        }

        // get key of the largest value from the array (comapring only the array values)
        // in our case, the array keys are the delimiters
        $results = array_keys($number_of_delimiter_occurences, max($number_of_delimiter_occurences));


        // in case the delimiter happens to be a 'tab' character ('\t'), return it in double quotes
        // otherwise when using as delimiter it will give an error,
        // because it is not recognised as a special character for 'tab' key,
        // it shows up like a simple string composed of '\' and 't' characters, which is not accepted when parsing csv files

        return $results[0] == '\t' ? "\t" : $results[0];
    }

    public function index()
    {
        $data = [];
        if ($this->import_model->count_all() > 0) {
            redirect('autoxadmin/import/tmptable');
        }
        if ($this->input->post()) {
            @unlink('./uploads/check_tecdoc.csv');
            $this->form_validation->set_rules('supplier_id', lang('text_supplier'), 'integer|required');
            if ($this->input->post('sample_id')) {
                $this->form_validation->set_rules('sample_id', lang('text_sample'), 'integer|required');
            } else {
                $this->form_validation->set_rules('sample_name', 'sample_name', 'trim|max_length[32]');
                $this->form_validation->set_rules('sample[sku]', 'sku', 'integer|required');
                $this->form_validation->set_rules('sample[brand]', 'brand', 'integer|required');
                $this->form_validation->set_rules('sample[quantity]', 'quantity', 'integer|required');
                $this->form_validation->set_rules('sample[currency_id]', 'currency', 'integer|required');
                $this->form_validation->set_rules('sample[delivery_price]', 'delivery_price', 'integer|required');
            }
            if ($this->form_validation->run() !== false) {
                if ($this->input->post('sample_save')) {
                    $save = [];
                    $save['supplier_id'] = (int)$this->input->post('supplier_id', true);
                    $save['name'] = $this->input->post('sample_name', true);
                    $save['value'] = serialize($this->input->post('sample'));
                    $this->sample_model->insert($save);
                }

                if ($this->input->post('sample_id')) {
                    $sample = unserialize($this->sample_model->get((int)$this->input->post('sample_id'))['value']);
                } else {
                    $sample = $this->input->post('sample');
                }

                //Загружаем прайс
                $config['upload_path'] = './uploads/import/';
                $config['allowed_types'] = '*';
                $config['file_ext_tolower'] = true;
                $config['encrypt_name'] = true;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('filename')) {


                    $upload_data = $this->upload->data();
                    $file_name = './uploads/import/' . $upload_data['file_name'];
                    //В зависимсти от типа файла запускаем его обработку
                    switch ($upload_data['file_ext']) {
                        case '.xls':
                            $this->xls_read($file_name, $sample, (int)$this->input->post('supplier_id', true));
                            break;
                        case '.csv':
                        case '.txt':
                            $params = array(
                                'file_name' => $file_name,
                                'sample' => $sample,
                                'supplier_id' => (int)$this->input->post('supplier_id', true),
                                'delimiter' => $this->detect_delimeter($file_name)
                            );

                            print_r($params);
                            exit('da');

                            $_SESSION['params'] = serialize($params);

                            $this->csv_read();
                            break;
                        default:
                            $this->error = 'Error file type';
                            break;
                    }
                } else {
                    $this->error = $this->upload->display_errors();
                }
            } else {
                $this->error = validation_errors();
            }
        }
        $data['suppliers'] = $this->supplier_model->get_all(false, false, false, ['name' => 'ASC']);
        $data['currency'] = $this->currency_model->get_all();
        $data['category'] = $this->category_model->get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/import/import', $data);
        $this->load->view('admin/footer');
    }

    public function tmptable()
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/import/tmptable');

        $config['per_page'] = 50;
        $data['importtmp'] = $this->import_model->get_importtmp($config['per_page'], $this->uri->segment(4));

        $data['total'] = $config['total_rows'] = $this->import_model->total_rows;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);


        if ($data['total'] == 0) {
            redirect('autoxadmin/import');
        }
        $data['file'] = '';
        if (file_exists('./uploads/check_tecdoc.csv')) {
            $data['file'] = '<a href="' . base_url('/uploads/check_tecdoc.csv') . '">Скачать файл с ошибками</a>';
        }
        $data['total_tecdoc'] = $this->db->where('id_art !=', 0)->count_all_results('importtmp');
        $data['total_no_tecdoc'] = $this->db->where('id_art =', 0)->count_all_results('importtmp');
        $data['supplier'] = $this->supplier_model->get_all();

        $this->load->view('admin/header');
        $this->load->view('admin/import/tmptable', $data);
        $this->load->view('admin/footer');
    }

    public function cancel()
    {
        $this->load->helper('file');
        delete_files('./uploads/import/');
        $this->import_model->truncate();
        $this->session->set_flashdata('success', lang('text_success_cancel'));
        redirect('autoxadmin/import');
    }

    public function add($id = 0)
    {
        if ($this->input->get('supplier_id') && $id == 0) {
            $supplier_id = (int)$this->input->get('supplier_id');
            switch ($this->input->get('settings')) {
                case 2:
                    $this->db->where('supplier_id', $supplier_id)->delete('product_price');
                    break;
            }
        }

        $products = $this->import_model->import_get_all();

        if ($products) {
            foreach ($products as $product) {

                $ids[] = $product['id'];
                if (!$product['product_id'] || $this->input->get('update_product_field')) {
                    $product_data = [
                        'sku' => $product['sku'],
                        'brand' => $product['brand'],
                        'name' => $product['name'],
                        'image' => $product['image'],
                        'slug' => $this->product_model->getSlug($product),
                        'category_id' => $product['category_id'],
                        'description' => $product['description'],
                    ];

                    $product_id = $this->product_model->product_insert($product_data, $this->input->get('update_product_field'), $this->input->get('update_seo_url'));

                } else {
                    $product_id = $product['product_id'];
                }

                if ($product_id) {
                    $price_data[] = [
                        'product_id' => $this->db->escape($product_id),
                        'excerpt' => $this->db->escape($product['excerpt']),
                        'currency_id' => $this->db->escape($product['currency_id']),
                        'delivery_price' => $this->db->escape($product['delivery_price']),
                        'saleprice' => $this->db->escape($product['saleprice']),
                        'quantity' => $this->db->escape($product['quantity']),
                        'supplier_id' => $this->db->escape($product['supplier_id']),
                        'term' => $this->db->escape($product['term']),
                        'created_at' => $this->db->escape(date("Y-m-d H:i:s")),
                        'updated_at' => $this->db->escape(date("Y-m-d H:i:s")),
                    ];

                    if ($product['attributes']) {
                        $this->product_attribute_model->delete($product_id);
                        $attribute_group = explode('|', $product['attributes']);
                        if ($attribute_group) {
                            foreach ($attribute_group as $ag) {
                                $attribute_values = explode(':', $ag);
                                if (isset($attribute_values[0]) && isset($attribute_values[1])) {
                                    $attributes_data[] = [
                                        'product_id' => $product_id,
                                        'attribute_name' => trim($attribute_values[0]),
                                        'attribute_value' => trim($attribute_values[1]),
                                        'category_id' => trim($product['category_id']),
                                        'attribute_slug' => url_title($attribute_values[0] . ' ' . $attribute_values[1])
                                    ];
                                }
                            }
                        }
                    }
                }

            }

            if (@$price_data) {
                $this->product_model->price_insert($price_data);
            }

            if (@$attribute_values) {
                $this->product_attribute_model->insert_batch($attributes_data);
            }

            $json = [
                'continue' => base_url('autoxadmin/import/add') . '/' . $product['id'] . '?supplier_id=' . $this->input->get('supplier_id') . '&update_product_field=' . $this->input->get('update_product_field') . '&update_seo_url=' . $this->input->get('update_seo_url'),
                'row' => $id
            ];

            $this->import_model->import_delete($ids);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($json));
        } else {
            //Чистим временную таблицу
            $this->import_model->truncate();
            //Удаляем загруженные файлы
            delete_files('./uploads/import/');

            //Чистим кэш
            $this->clear_cache();

            $this->session->set_flashdata('success', lang('text_success'));

            $json = [
                'success' => base_url('autoxadmin/import')
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($json));
        }
    }

    public function checktecdoc($id = 0)
    {
        if ($id == 0) {
            @unlink('./uploads/check_tecdoc.csv');
            if (isset($_SESSION['continue_product_id'])) {
                $id = $_SESSION['continue_product_id'];
            }
        }
        $json = [];

        $products = $this->import_model->check_get_all($id);
        $synonym_names = $this->synonym_name_model->get_synonym_names();
        if ($products) {
            foreach ($products as $product) {
                $key = md5($product['sku'] . $product['brand']);
                $td[$key] = ['sku' => $product['sku'], 'brand' => $product['brand']];
            }

            $tecdoc_info_array = (array)$this->tecdoc->getArticleArray($td);
        }

        if ($products) {
            $delete = [];
            foreach ($products as $product) {

                $key = md5($product['sku'] . $product['brand']);

                $save = [];
                $save['id_art'] = 0;

                if (isset($tecdoc_info_array[$key])) {
                    $save['id_art'] = (int)$tecdoc_info_array[$key]->ID_art;
                    if (!$product['name']) {
                        $save['name'] = trim($tecdoc_info_array[$key]->Name);
                        if ($synonym_names && isset($synonym_names[$save['name']])) {
                            $save['name'] = $synonym_names[$save['name']];
                        }
                    }
                    $this->import_model->insert($save, $product['id']);
                } else {
                    if ($id == 0) {
                        $fp = fopen('./uploads/check_tecdoc.csv', 'w');
                        fputcsv($fp, array_keys($product));
                        $id = $product['id'];
                    } else {
                        $fp = fopen('./uploads/check_tecdoc.csv', 'a');
                    }
                    fputcsv($fp, $product);
                    $delete[] = $product['id'];
                }
            }

            if($delete){
                $this->db->where_in('id', $delete)->delete('importtmp');
            }

            $_SESSION['continue_product_id'] = $product['id'];
            $json = [
                'continue' => base_url('/autoxadmin/import/checktecdoc') . '/' . $product['id'],
                'row' => $id
            ];
        } else {
            if (isset($_SESSION['continue_product_id'])) {
                unlink($_SESSION['continue_product_id']);
            }
            unset($_SESSION['continue_product_id']);
            if (file_exists('./uploads/check_tecdoc.csv')) {
                $this->session->set_flashdata('error', '<a href="' . base_url('/uploads/check_tecdoc.csv') . '">Скачать файл с ошибками</a>');
            } else {
                $this->session->set_flashdata('success', 'Проверка прошла успешно. Ошибок не обнаружено');
            }

        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function csv_read()
    {
        $params = unserialize($_SESSION['params']);

        $synonyms = $this->synonym_model->get_synonyms();
        $synonym_names = $this->synonym_name_model->get_synonym_names();

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
            while (($data_f = fgetcsv($handle_f, 1000, $params['delimiter'])) !== false) {
                $encoding = mb_detect_encoding(@$data_f[$params['sample']['name'] - 1], mb_detect_order(), true);

                if ($encoding != 'UTF-8') {
                    $data_f = array_map(function ($text) {
                        return iconv('WINDOWS-1251', "UTF-8", $text);
                    }, $data_f);
                }

                if (isset($data_f[$params['sample']['sku'] - 1])) {
                    $sku = $data_f[$params['sample']['sku'] - 1];
                    if ($params['sample']['default_regular'] != '') {
                        $sku = preg_replace('/' . $params['sample']['default_regular'] . '/', '', $sku);
                    }
                    $sku = $this->product_model->clear_sku($sku);
                } else {
                    $sku = '';
                }
                if (isset($data_f[$params['sample']['brand'] - 1])) {
                    $brand = $this->product_model->clear_brand($data_f[$params['sample']['brand'] - 1], $synonyms);
                } else {
                    $brand = '';
                }


                if (isset($data_f[$params['sample']['name'] - 1])) {
                    $name = trim($data_f[$params['sample']['name'] - 1]);
                } else {
                    $name = '';
                }

                if ($synonym_names && isset($synonym_names[$name])) {
                    $name = $synonym_names[$name];
                }

                if (isset($data_f[$params['sample']['quantity'] - 1])) {
                    $quantity = $this->product_model->clear_quan($data_f[$params['sample']['quantity'] - 1]);
                } else {
                    $quantity = 0;
                }

                if (isset($data_f[$params['sample']['delivery_price'] - 1])) {
                    $delivery_price = $this->product_model->clear_price($data_f[$params['sample']['delivery_price'] - 1]);
                } else {
                    $delivery_price = 0;
                }

                if (isset($data_f[(int)$params['sample']['saleprice'] - 1])) {
                    $saleprice = $this->product_model->clear_price($data_f[$params['sample']['saleprice'] - 1]);
                } else {
                    $saleprice = 0;
                }


                if (isset($data_f[(int)$params['sample']['description'] - 1])) {
                    $description = trim($data_f[$params['sample']['description'] - 1]);
                } else {
                    $description = '';
                }

                if (isset($data_f[(int)$params['sample']['excerpt'] - 1])) {
                    $excerpt = trim($data_f[$params['sample']['excerpt'] - 1]);
                } else {
                    $excerpt = @$params['sample']['default_excerpt'];
                }

                if (isset($data_f[(int)$params['sample']['term'] - 1])) {
                    $term = (int)$data_f[$params['sample']['term'] - 1];
                    if (substr($params['sample']['default_term'], 0, 1) == '+') {
                        $term += substr($params['sample']['default_term'], 1);
                    } else if (substr($params['sample']['default_term'], 0, 1) == '-') {
                        $term -= substr($params['sample']['default_term'], 1);
                    }

                    if (isset($params['sample']['default_term_unit']) && $params['sample']['default_term_unit'] == 'day') {
                        $term = $term * 24;
                    }
                } else {
                    $term = $params['sample']['default_term'];
                    if (isset($params['sample']['default_term_unit']) && $params['sample']['default_term_unit'] == 'day') {
                        $term = $term * 24;
                    }
                }

                if (isset($data_f[(int)$params['sample']['category'] - 1]) && (int)$data_f[$params['sample']['category'] - 1] > 0) {
                    $category_id = (int)$data_f[$params['sample']['category'] - 1];
                } else {
                    $category_id = (int)@$params['sample']['default_category_id'];
                }

                if (isset($data_f[(int)$params['sample']['image'] - 1])) {
                    $image = $data_f[$params['sample']['image'] - 1];
                } else {
                    $image = '';
                }

                if (isset($data_f[(int)$params['sample']['attributes'] - 1])) {
                    $attributes = $data_f[$params['sample']['attributes'] - 1];
                } else {
                    $attributes = '';
                }

                $currency_id = $params['sample']['currency_id'];

                $save[] = [
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
                    'image' => $image,
                    'attributes' => $attributes
                ];

                if ($i == 2000) {
                    $this->import_model->insert_batch($save);
                    $url = base_url('autoxadmin/import/csv_read');
                    echo('<html>
                    <head>
                    <title>Загрузка</title>
                    </head>
                    <body>
                    Идет загрузка.<br /><a id="go" href=\'' . $url . '?x=' . $x . '&ftell=' . ftell($handle_f) . '\'>.</a>
                    <script type="text/javascript">document.getElementById(\'go\').click();</script>
                    </body>
                    </html>');
                    die();
                }
                $x++;
                $i++;
            }
            if (count($save)) {
                $this->import_model->insert_batch($save);
                $this->finish($params['supplier_id']);
            }
            fclose($handle_f);
        }
    }

    private function finish($supplier_id)
    {
        //Очищаем сессию params
        if (isset($_SESSION['params'])) {
            unset($_SESSION['params']);
        }

        //Очищаем сессию проверку текдок
        if (isset($_SESSION['continue_product_id'])) {
            @unlink($_SESSION['continue_product_id']);
        }

        //Удаляем позиции в которых цена 0 или наличие 0
        $this->import_model->clear_importtmp();

        //Обновляем дату последнего обновления у поставщика
        $this->supplier_model->insert(['updated_at' => date("Y-m-d H:i:s")], $supplier_id);

        if ($this->import_model->count_all() > 0) {
            $this->session->set_flashdata('success', lang('text_success_import'));
            redirect('autoxadmin/import/tmptable?supplier_id=' . $supplier_id);
        } else {
            $this->session->set_flashdata('error', lang('text_error_import'));
            redirect('autoxadmin/import');
        }
    }

    public function xls_read($file_name, $sample, $supplier_id)
    {
        //Подключаме бтблиотеку для работы с xls
        error_reporting(E_ALL ^ E_NOTICE);
        require_once APPPATH . 'libraries/excel_reader2.php';
        $synonyms = $this->synonym_model->get_synonyms();
        $synonym_names = $this->synonym_name_model->get_synonym_names();

        $excel = new Spreadsheet_Excel_Reader($file_name, false);
        if ($excel->sheets[0]['numRows'] > 0) {
            $save = [];
            $q = 0;
            for ($i = 1; $i <= $excel->sheets[0]['numRows']; $i++) {
                $sku = $excel->sheets[0]['cells'][$i][$sample['sku']];
                if ($sample['default_regular'] != '') {
                    $sku = preg_replace('/' . $sample['default_regular'] . '/', '', $sku);
                }
                $sku = $this->product_model->clear_sku($sku);
                $brand = $this->product_model->clear_brand($excel->sheets[0]['cells'][$i][$sample['brand']], $synonyms);
                $name = trim($excel->sheets[0]['cells'][$i][$sample['name']]);
                if ($synonym_names && isset($synonym_names[$name])) {
                    $name = $synonym_names[$name];
                }
                $quantity = $this->product_model->clear_quan($excel->sheets[0]['cells'][$i][$sample['quantity']]);
                $delivery_price = $this->product_model->clear_price($excel->sheets[0]['cells'][$i][$sample['delivery_price']]);
                $saleprice = $this->product_model->clear_price($excel->sheets[0]['cells'][$i][$sample['saleprice']]);
                if (!empty($sample['term'])) {
                    $term = (int)$excel->sheets[0]['cells'][$i][$sample['term']];

                    if (substr($sample['default_term'], 0, 1) == '+') {
                        $term += substr($sample['default_term'], 1);
                    } else if (substr($sample['default_term'], 0, 1) == '-') {
                        $term -= substr($sample['default_term'], 1);
                    }

                    if (isset($sample['default_term_unit']) && $sample['default_term_unit'] == 'day') {
                        $term = $term * 24;
                    }

                } else {
                    $term = $sample['default_term'];
                    if (isset($sample['default_term_unit']) && $sample['default_term_unit'] == 'day') {
                        $term = $term * 24;
                    }
                }
                if (!empty($sample['category'])) {
                    $category_id = (int)$excel->sheets[0]['cells'][$i][$sample['category']];
                    if (empty($category_id)) {
                        $category_id = $sample['default_category_id'];
                    }
                } else {
                    $category_id = $sample['default_category_id'];
                }
                $description = trim($excel->sheets[0]['cells'][$i][$sample['description']]);
                $excerpt = trim($excel->sheets[0]['cells'][$i][$sample['excerpt']]);
                if (mb_strlen($excerpt) == 0) {
                    $excerpt = @$sample['default_excerpt'];
                }
                $image = trim($excel->sheets[0]['cells'][$i][$sample['image']]);
                $attributes = trim($excel->sheets[0]['cells'][$i][$sample['attributes']]);
                $currency_id = $sample['currency_id'];

                $save[] = [
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
                    'image' => $image,
                    'attributes' => $attributes
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