<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class supplier extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/supplier');
        $this->load->model('supplier_model');
        $this->load->model('pricing_model');
        $this->load->model('product_model');
        $this->load->model('customergroup_model');
    }

    public function index()
    {
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/supplier/index');
        $config['per_page'] = 20;
        $data['supplieres'] = $this->supplier_model->get_suppliers($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->supplier_model->total_rows;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $this->load->view('admin/header');
        $this->load->view('admin/supplier/supplier', $data);
        $this->load->view('admin/footer');
    }

    public function create()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('description', lang('text_description'), 'max_length[3000]');
            $this->form_validation->set_rules('stock', lang('text_stock'), 'integer');
            $this->form_validation->set_rules('api', lang('text_api'), 'max_length[12]');
            if ($this->form_validation->run() !== false) {
                $this->save_data();
            } else {
                $this->error = validation_errors();
            }
        }
        $data['groups'] = $this->customergroup_model->get_group();

        $this->load->view('admin/header');
        $this->load->view('admin/supplier/create',$data);
        $this->load->view('admin/footer');
    }

    public function edit($id)
    {
        $data = [];
        $data['supplier'] = $this->supplier_model->get($id);

        if (!$data['supplier']) {
            show_404();
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('description', lang('text_description'), 'max_length[3000]');
            $this->form_validation->set_rules('stock', lang('text_stock'), 'integer');
            $this->form_validation->set_rules('api', lang('text_api'), 'max_length[12]');
            if ($this->form_validation->run() !== false) {
                $this->save_data($id);
            } else {
                $this->error = validation_errors();
            }
        }
        //Ценообразование по постащику
        $data['pricing'] = $this->pricing_model->get_by_supplier($id);
        //Статистика по поставщику
        $data['count'] = $this->product_model->product_count_all(['supplier_id' => (int)$id]);

        $data['groups'] = $this->customergroup_model->get_group();

        $this->load->view('admin/header');
        $this->load->view('admin/supplier/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        $this->supplier_model->delete($id);
        //Удаляем ценообразование
        $this->db->where('supplier_id', (int)$id)->delete('pricing');
        //Удаляем товары
        $this->db->where('supplier_id', (int)$id)->delete('product_price');
        //Удаляем шаблоны
        $this->db->where('supplier_id', (int)$id)->delete('sample');

        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/supplier');
    }

    public function delete_products($id){
        //Удаляем товары
        $this->db->where('supplier_id', (int)$id)->delete('product_price');
        //Чистим кэш
        $this->clear_cache();
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/supplier/edit/'.$id);
    }

    private function save_data($id = false)
    {
        $save = [];
        $save['name'] = $this->input->post('name', true);
        $save['description'] = $this->input->post('description', true);
        $save['stock'] = (bool)$this->input->post('stock', true);
        $save['api'] = (string)$this->input->post('api', true);
        $supplier_id = $this->supplier_model->insert($save, $id);
        if ($supplier_id) {
            $pricing = $this->input->post('pricing');
            //Удаляем ценобразование
            $this->db->where('supplier_id', (int)$supplier_id)->delete('pricing');
            if (!empty($pricing)) {
                foreach ($this->input->post('pricing', true) as $pricing) {
                    if (($pricing['value'] > 0 || $pricing['fix_value'] > 0) && $pricing['price_from'] >= 0 && $pricing['price_to'] > 0 ) {
                        $save = [];
                        $save['supplier_id'] = $supplier_id;
                        $save['brand'] = (string)$pricing['brand'];
                        $save['customer_group_id'] = (int)$pricing['customer_group_id'];
                        $save['price_from'] = (float)$pricing['price_from'];
                        $save['price_to'] = (float)$pricing['price_to'];
                        $save['method_price'] = (string)$pricing['method_price'];
                        $save['value'] = (int)$pricing['value'];
                        $save['fix_value'] = (float)$pricing['fix_value'];
                        $this->pricing_model->insert($save);
                    }
                }
            }

            $this->session->set_flashdata('success', lang('text_success'));
            if($id){
                redirect('autoxadmin/supplier/edit/'.$id);
            }else{
                redirect('autoxadmin/supplier');
            }

        }
    }



    public function get_sample()
    {
        $this->load->language('admin/import');

        $fields = [
            'currency_id' => lang('text_sample_currency'),
            'default_category_id' => lang('text_sample_default_category'),
            'default_excerpt' => lang('text_sample_default_excerpt'),
            'default_term' => lang('text_sample_default_term'),
            'default_term_unit' => lang('text_sample_term_unit'),
            'default_regular' => 'Очистка номера',
            'sku' => lang('text_sample_sku'),
            'brand' => lang('text_sample_brand'),
            'name' => lang('text_sample_name'),
            'description' => lang('text_sample_description'),
            'excerpt' => lang('text_sample_excerpt'),
            'delivery_price' => lang('text_sample_delivery_price'),
            'saleprice' => lang('text_sample_saleprice'),
            'quantity' => lang('text_sample_quantity'),
            'term'=> lang ('text_sample_term'),
            'category' => lang('text_sample_category'),
            'image' => lang('text_sample_image'),
            'attributes' => lang('text_sample_attributes')
        ];
        $html = '';
        if ($this->input->is_ajax_request()) {
            $this->load->model('sample_model');
            $supplier_id = (int)$this->input->post('supplier_id', true);
            $results = $this->sample_model->get_all(false, false, array('supplier_id' => $supplier_id));
            if ($results) {
                foreach($results as $item){

                    $html .= '<div class="radio" id="sample_'.$item['id'].'">';
                    $html .= '<div class="pull-right"><button class="btn btn-danger btn-xs" onclick="delete_sample('.$item['id'].',event)"><i class="fa fa-remove"></i></button> <button class="btn btn-info btn-xs" onclick="edit_sample('.$item['id'].',event)"><i class="glyphicon glyphicon-pencil"></i></button></div>';
                    $html .= '    <label>';
                    $html .= '        <input type="radio" name="sample_id" value="'.$item['id'].'">';
                    $html .= '            <b>'.$item['name'].'</b>';
                    $html .= '            <p class="help-block">';
                    foreach(unserialize($item['value']) as  $index => $value ) {
                        $html .= $fields[$index].' : <b>'.$value.'</b><br>';
                    }
                    $html .= '</p>';
                    $html .= '    </label>';
                    $html .= '</div>';
                }

            } else {
                $html = 'No sample';
            }
        } else {
            exit('error');
        }

        exit($html);

    }

    public function delete_sample(){
        if ($this->input->is_ajax_request()) {
            $sample_id = $this->input->post('sample_id');
            if($sample_id){
                $this->load->model('sample_model');
                $this->sample_model->delete((int)$sample_id);
            }
        }
    }

    public function edit_sample(){
        $id = $this->input->post('sample_id');
        $this->load->model('sample_model');

        $sample = $this->sample_model->get($id);

        if($sample){
            $sample['value'] = unserialize($sample['value']);

            $this->output
                ->set_content_type('application/html')
                ->set_output(json_encode($sample));
        }
    }
}