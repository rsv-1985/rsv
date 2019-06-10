<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_tool extends Admin_controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/tools/product_tool');
    }

    public function index()
    {

        if ($_FILES) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'csv';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('userfile')) {
                $upload_data = $this->upload->data();
                $file_name = './uploads/' . $upload_data['file_name'];
                $this->_prepare($file_name);
            } else {
                $this->error = $this->upload->display_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/tools/product_tool/index');
        $this->load->view('admin/footer');
    }

    public function export($id = 0)
    {
        $this->load->model('mproduct');
        $this->load->model('attribute_model');
        $attributes = $this->attribute_model->getAttributes();
        $attr_fields = [];
        if ($attributes) {
            foreach ($attributes as $attribute) {
                $attr_fields[] = $attribute['name'];
            }
        }

        if ($id == 0) {
            @unlink('./uploads/export_product_tool.csv');
            $data = array_merge(['Артикул', 'Бренд', 'Название', 'Категория', 'Картинка'], $attr_fields);
            $this->_write_file([$data]);
        }

        $products = $this->mproduct->getByQuery("SELECT p.*, c.name as category FROM ax_product p LEFT JOIN ax_category c ON c.id = p.category_id WHERE p.id > " . (int)$id . " ORDER BY p.id LIMIT 1000");
        $list = [];
        if ($products) {
            foreach ($products as $product) {
                $data = [$product->sku, $product->brand, $product->name, $product->category];
                if ($attr_fields) {
                    $attributes = $product->getAttributes();
                    if ($attributes) {
                        foreach ($attr_fields as $attr_field) {
                            foreach ($attributes as $attribute) {
                                if ($attribute['attribute_name'] == $attr_field) {
                                    $data[] = $attribute['values'];
                                } else {
                                    $data[] = '';
                                }
                            }
                        }

                    }
                }
                $list[] = $data;
                $id = $product->id;
            }

            if ($list) {
                $this->_write_file($list);
            }

            $url = base_url('autoxadmin/tools/product_tool/export/' . $id);
            echo('<html>
                    <head>
                    <title>Загрузка</title>
                    </head>
                    <body>
                    Идет загрузка.<br /><a id="go" href=\'' . $url . '\'>.</a>
                    <script type="text/javascript">document.getElementById(\'go\').click();</script>
                    </body>
                    </html>');
            die();
        }
    }

    private function _write_file($list, $type = 'a')
    {
        $fp = fopen('./uploads/export_product_tool.csv', $type);
        foreach ($list as $fields) {
            fputcsv($fp, $fields, ';');
        }
        fclose($fp);
    }

    private function _prepare($file_name)
    {

        $lines = file($file_name);
        $ids_attr = [];
        $row = 0;
        $heading = [];
        $heading[] = 'Артикул';
        $heading[] = 'Бренд';
        $heading[] = 'Название';
        $heading[] = 'Описание';
        $heading[] = 'Категория';
        $heading[] = 'Картинка';
        $products = [];
        foreach ($lines as $line) {
            $csv = str_getcsv($line, ';');

            $encoding = mb_detect_encoding($csv[3],mb_detect_order(),true);

            if($encoding != 'UTF-8'){
                $data_f = array_map(function($text){
                    return iconv('WINDOWS-1251', "UTF-8", $text);
                },$csv);
            }

            if ($row == 0) {
                //Првоеряем указаны ли аттрибуты в файле
                if (count($attributes = array_slice($csv, 6))) {
                    foreach ($attributes as $attribute) {
                        $heading[] = $attribute;
                    }
                }
            } else {
                $products[] = $csv;
            }

            $row++;
            if ($row == 30) {
                break;
            }
        }

        if ($products) {
            $this->load->view('admin/header');
            $this->load->view('admin/tools/product_tool/prepare', ['products' => $products, 'heading' => $heading, 'file_name' => $file_name]);
            $this->load->view('admin/footer');
        } else {
            unlink($file_name);
            $this->session->set_flashdata('error', 'Не удалось найти товары в файле');
            redirect('/autoxadmin/tools/product_tool');
        }
    }

    public function cancel()
    {
        @unlink($this->input->get('file_name'));
        redirect('/autoxadmin/tools/product_tool');
    }

    public function import()
    {
        $this->load->model('admin/mproduct');

        $count_create_product = 0;
        $count_create_attribute = 0;
        $count_create_attribute_value = 0;

        $file_name = $this->input->post('file_name');
        $update_product = $this->input->post('update_product');
        $update_seo_url = $this->input->post('update_seo_url');
        $create_attr = $this->input->post('create_attr');



        $this->load->model('attribute_model');
        $this->load->model('product_model');

        $lines = file($file_name);
        $ids_attr = [];
        $row = 0;

        foreach ($lines as $line) {



            $csv = str_getcsv($line, ';');

            $encoding = mb_detect_encoding($csv[3],mb_detect_order(),true);

            if($encoding != 'UTF-8'){
                $data_f = array_map(function($text){
                    return iconv('WINDOWS-1251', "UTF-8", $text);
                },$csv);
            }

            if ($row == 0) {
                //Првоеряем указаны ли аттрибуты в файле
                if (count($attributes = array_slice($csv, 6))) {
                    foreach ($attributes as $attribute) {
                        //Проверяем есть ли аттрибут в базе
                        $check_attr = $this->db->where('name', trim($attribute))->get('attribute')->row_array();
                        if ($check_attr) {
                            $ids_attr[] = $check_attr['id'];
                        } else {
                            if ($create_attr) {
                                $count_create_attribute++;
                                $this->db->insert('attribute', ['name' => trim($attribute)]);
                                $ids_attr[] = $this->db->insert_id();
                            } else {
                                $ids_attr[] = '';
                            }
                        }
                    }
                }
            } else {
                if(count($csv) < 6){
                    continue;
                }
                $category = $this->db->where('name', $csv[4])->get('category')->row_array();

                $images = [];

                if ($csv[5]) {
                    $images = explode(',', $csv[5]);
                }

                $product = [
                    'sku' => clear_sku($csv[0]),
                    'brand' => clear_brand($csv[1]),
                    'name' => $csv[2],
                    'description' => $csv[3],
                    'category_id' => $category ? $category['id'] : 0,
                    'image' => $images ? array_shift($images) : '',
                ];

                if($update_seo_url){
                    $product['slug'] = $this->product_model->getSlug($product);
                }

                $product_id = $this->product_model->product_insert($product, $update_product,$update_seo_url);


                if($product_id){
                    if (count($images)) {

                        $this->mproduct->deleteImages($product_id);

                        foreach ($images as $image){
                            $this->db->insert('product_images',['product_id' => $product_id, 'image' => $image]);
                        }
                    }

                    if (count($attributes = array_slice($csv, 6))) {
                        //Удаялем старые значения аттрибутов
                        $this->mproduct->deleteAttributes($product_id);

                        $batch_attributes = [];

                        foreach ($attributes as $key => $attribute) {
                            $attr_value_id = false;

                            if($ids_attr[$key]){
                                $attribute_id = $ids_attr[$key];
                                //Проверяем есть ли значение аттрибута в базе
                                $this->db->where('attribute_id',(int)$ids_attr[$key]);
                                $this->db->where('value', trim($attribute));
                                $result = $this->db->get('attribute_value')->row_array();
                                if($result){
                                    $attr_value_id = $result['id'];
                                }else{
                                    if($create_attr){
                                        $this->db->insert('attribute_value',['attribute_id' => $ids_attr[$key], 'value' => trim($attribute)]);
                                        $attr_value_id = $this->db->insert_id();
                                    }
                                }

                                if($attr_value_id){
                                    $batch_attributes[] = [
                                        'product_id' => $product_id,
                                        'attribute_id' => $attribute_id,
                                        'attribute_value_id' => $attr_value_id
                                    ];
                                }
                            }
                        }

                        if($batch_attributes){
                            $this->mproduct->addAttributesBatch($batch_attributes);
                        }
                    }
                }
            }

            $row++;
        }

        unlink($file_name);
        $this->session->set_flashdata('success', 'Импорт прошел успешно.');
        redirect('/autoxadmin/tools/product_tool');
    }
}