<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_group extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/brand_group');
        $this->load->model('brand_group_model');
        $this->load->model('product_model');

    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/brand_group/index');
        $config['total_rows'] = $this->brand_group_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['groups'] = $this->brand_group_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/brand_group/brand_group', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        if($this->input->post()){
            $this->form_validation->set_rules('brands', lang('text_brands'), 'required|trim');
            $this->form_validation->set_rules('group_name', lang('text_group_name'), 'required|max_length[64]|trim');
            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/brand_group/create');
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['brand_group'] = $this->brand_group_model->get($id);
        if(!$data['brand_group']){
            show_404();
        }
        $brands = $this->brand_group_model->getBrands($id);
        if($brands){
            $items = [];
            foreach ($brands as $brand){
                $items[] = $brand['brand'];
            }

            $data['brands'] = implode(';',$items);
        }else{
            $data['brands'] = false;
        }


        if($this->input->post()){

            $this->form_validation->set_rules('brands', lang('text_brands'), 'required|trim');
            $this->form_validation->set_rules('group_name', lang('text_group_name'), 'required|max_length[64]|trim');
            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/brand_group/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->brand_group_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/brand_group');
    }

    private function save_data($id = false){
        $this->load->model('product_model');
        $error = [];
        $save = [];
        $save['group_name'] = $this->input->post('group_name', true);
        $id = $this->brand_group_model->insert($save, $id);
        if($id){
            $this->brand_group_model->deleteBrands($id);
            $brands = explode(';',$this->input->post('brands',true));
            foreach (array_unique($brands) as $brand){
                $brand = $this->product_model->clear_brand($brand);
                if($brand){
                    //Проверяем не используетсяли бренд в других группах
                    $check = $this->brand_group_model->getBrandGroupByBrand($brand);
                    if($check && $check['id'] != $id){
                        $error[] = $brand.' используется в группе '.$check['group_name'].' система его пропустила.';
                        continue;
                    }
                    $save = [];
                    $save['group_brand_id'] = $id;
                    $save['brand'] = $brand;
                    $this->brand_group_model->addBrand($save);
                }
            }

            if($error){
                $this->session->set_flashdata('error', implode('<br>',$error));
            }else{
                $this->session->set_flashdata('success', lang('text_success'));
            }

            redirect('autoxadmin/brand_group');
        }
    }
}