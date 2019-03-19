<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/attribute');
        $this->load->model('attribute_model');
    }

    public function delete_value($id){
        //Удаляем с базы
        $this->db->where('id', (int)$id)->delete('attribute_value');
        //Удаляем с товаров
        $this->db->where('attribute_value_id', (int)$id)->delete('product_attribute');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/attribute/index');
        $config['total_rows'] = $this->attribute_model->count_all();
        $config['per_page'] = 50;

        $this->pagination->initialize($config);

        $data['attributes'] = $this->attribute_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/attribute/attribute', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'max_length[255]|trim|required');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/attribute/create');
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['attribute'] = $this->attribute_model->get($id);
       
        if(!$data['attribute']){
            show_404();
        }

        $data['values'] = $this->attribute_model->getValues($id);

        if($this->input->post()){
            $this->form_validation->set_rules('name', 'Название опции', 'max_length[255]|trim|required');

            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/attribute/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->attribute_model->deleteAttr($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/attribute');
    }

    private function save_data($id = false){
        $save = [];
        $save['name'] = $this->input->post('name', true);
        $save['sort_order'] = (int)$this->input->post('sort_order');
        $save['in_filter'] = (bool)$this->input->post('in_filter');
        $save['in_short_description'] = (bool)$this->input->post('in_short_description');
        $save['max_height'] = (int)$this->input->post('max_height');

        $id = $this->attribute_model->insert($save, $id);

        if($id){
            $this->attribute_model->deleteValues($id);

            if($this->input->post('attributes')){
                foreach ($this->input->post('attributes') as $attribute){
                    $attribute['attribute_id'] = (int)$id;

                    $this->attribute_model->addValue($attribute);
                }
            }
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/attribute');
        }
    }
}