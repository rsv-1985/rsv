<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');


class Product extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/product');
        $this->load->model('product_model');
        $this->load->model('product_attribute_model');
        $this->load->model('category_model');
        $this->load->model('supplier_model');
        $this->load->model('pricing_model');
        $this->load->model('currency_model');
        $this->load->model('synonym_model');
    }

    public function index(){
        $data = [];

        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/product/index');

        $config['per_page'] = 10;
        $data['products'] = $this->product_model->admin_product_get_all($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->product_model->total_rows;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        if($this->input->post()){
            $this->form_validation->set_rules('delivery_price', lang('text_delivery_price'), 'required|numeric|trim');
            $this->form_validation->set_rules('price', lang('text_price'), 'required|numeric|trim');
            $this->form_validation->set_rules('saleprice', lang('text_saleprice'), 'numeric|trim');
            $this->form_validation->set_rules('status', lang('text_status'), 'numeric|trim');
            if ($this->form_validation->run() !== false){
                $save = [];
                $save['delivery_price'] = (float)$this->input->post('delivery_price', true);
                $save['price'] = (float)$this->input->post('price', true);
                $save['saleprice'] = (float)$this->input->post('saleprice', true);
                $save['status'] = (bool)$this->input->post('status', true);
                $product_id = (int)$this->input->post('product_id');
                $supplier_id = (int)$this->input->post('supplier_id');
                $term = (int)$this->input->post('term');
                $this->product_model->update_item($save, $product_id, $supplier_id, $term);
                $this->session->set_flashdata('success', lang('text_success'));
                redirect('autoxadmin/product');
            }else{
                $this->error = validation_errors();
            }
        }
        $data['supplier'] = $this->supplier_model->supplier_get_all();
        $data['currency'] = $this->currency_model->currency_get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/product/product', $data);
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['product'] = $this->product_model->get($id);
        if(!$data['product']){
            redirect('autoxadmin/product');
        }
        $data['prices'] = $this->product_model->get_product_price($id);
        $data['attributes'] = $this->product_attribute_model->get_product_attributes($id);

        if($this->input->post()){
            $this->form_validation->set_rules('sku', lang('text_sku'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('brand', lang('text_brand'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('name', lang('text_name'), 'max_length[155]|trim');
            $this->form_validation->set_rules('image', lang('text_image'), 'max_length[255]|trim');
            $this->form_validation->set_rules('h1', lang('text_h1'), 'max_length[250]|trim');
            $this->form_validation->set_rules('title', lang('text_title'), 'max_length[250]|trim');
            $this->form_validation->set_rules('meta_description', lang('text_meta_description'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('meta_keywords', lang('text_meta_keywords'), 'max_length[250]|trim');
            $this->form_validation->set_rules('bought', lang('text_bought'), 'integer');
            $this->form_validation->set_rules('category_id', lang('text_category_id'), 'integer');

            if($this->input->post('slug') != $data['product']['slug']){
                $this->form_validation->set_rules('slug', lang('text_slug'), 'max_length[255]|trim|is_unique[product.slug]');
            }
            if($this->input->post('prices') && $_POST['prices'][0]['supplier_id']){
                foreach ($this->input->post('prices') as $i => $item){
                    $this->form_validation->set_rules('prices['.$i.'][description]', lang('text_description'), 'max_length[12000]|trim');
                    $this->form_validation->set_rules('prices['.$i.'][excerpt]', lang('text_excerpt'), 'max_length[32]|trim');
                    $this->form_validation->set_rules('prices['.$i.'][currency_id]', $i.lang('text_currency_id'), 'required|integer');
                    $this->form_validation->set_rules('prices['.$i.'][delivery_price]', lang('text_delivery_price'), 'required|numeric');
                    $this->form_validation->set_rules('prices['.$i.'][saleprice]', lang('text_saleprice'), 'numeric');
                    $this->form_validation->set_rules('prices['.$i.'][price]', lang('text_price'), 'required|numeric');
                    $this->form_validation->set_rules('prices['.$i.'][quantity]', lang('text_quantity'), 'integer');
                    $this->form_validation->set_rules('prices['.$i.'][supplier_id]', lang('text_supplier_id'), 'required|integer');
                    $this->form_validation->set_rules('prices['.$i.'][term]', lang('text_term'), 'integer');
                }
            }




            if ($this->form_validation->run() !== false){
                $file_name = $this->input->post('image');
                if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
                    $config['upload_path']          = './uploads/product/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['encrypt_name']         = true;

                    $this->load->library('upload', $config);

                    if($this->upload->do_upload('userfile')){
                        $upload_data = $this->upload->data();
                        $file_name = $upload_data['file_name'];
                        @unlink('./uploads/product/'.$data['product']['image']);
                    }
                    else{
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        redirect('autoxadmin/product/edit/'.$slug);
                    }
                }else{
                    if(mb_strlen($file_name) == 0){
                        @unlink('./uploads/product/'.$data['product']['image']);
                    }
                }
                $this->save_data($id, $file_name);
            }else{
                $this->error = validation_errors();
            }
        }
        $data['supplier'] = $this->supplier_model->supplier_get_all();
        $data['currency'] = $this->currency_model->currency_get_all();
        $data['category'] = $this->category_model->admin_category_get_all();

        $this->load->view('admin/header');
        $this->load->view('admin/product/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete_product_cart(){
        $this->db->query('DELETE FROM `ax_product_price` WHERE supplier_id = 0');
        //$this->db->query("DELETE a FROM `ax_product` a LEFT JOIN `ax_product_price` b ON a.id=b.product_id WHERE b.product_id IS NULL");
        $this->db->query('DELETE FROM ax_product WHERE id NOT IN (SELECT product_id FROM ax_product_price GROUP BY product_id)');
        $this->session->set_flashdata('success', lang('text_success'));
        $this->clear_cache();
        redirect('autoxadmin/product');
    }

    public function delete(){
        $product_id = (int)$this->input->get('product_id');
        $supplier_id = (int)$this->input->get('supplier_id');
        $term = (int)$this->input->get('term');
        if($product_id && $supplier_id) {
            $this->product_model->product_delete(['product_id' => (int)$product_id, 'supplier_id' => (int)$supplier_id, 'term' => (int)$term]);
            $this->session->set_flashdata('success', lang('text_success'));
        }else{
            $this->session->set_flashdata('error', 'ERROR DELETE !');
        }
        $this->clear_cache();
        redirect('autoxadmin/product');
    }

    public function create(){
        if($this->input->post()){
            if(!$this->input->post('slug')){
                $_POST['slug'] = $this->product_model->getSlug([
                    'name' => $this->input->post('name',true),
                    'sku' => $this->input->post('sku',true),
                    'brand' => $this->input->post('brand',true),
                ]);
            }
            $this->form_validation->set_rules('sku', lang('text_sku'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('brand', lang('text_brand'), 'required|max_length[32]|trim');
            $this->form_validation->set_rules('name', lang('text_name'), 'max_length[155]|trim');
            $this->form_validation->set_rules('image', lang('text_image'), 'max_length[255]|trim');
            $this->form_validation->set_rules('h1', lang('text_h1'), 'max_length[250]|trim');
            $this->form_validation->set_rules('title', lang('text_title'), 'max_length[250]|trim');
            $this->form_validation->set_rules('meta_description', lang('text_meta_description'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('meta_keywords', lang('text_meta_keywords'), 'max_length[250]|trim');
            $this->form_validation->set_rules('bought', lang('text_bought'), 'integer');
            $this->form_validation->set_rules('category_id', lang('text_category_id'), 'integer');
            $this->form_validation->set_rules('slug', lang('text_slug'), 'max_length[255]|trim|is_unique[product.slug]');

            if($this->input->post('prices')){
                foreach ($this->input->post('prices') as $i => $item){
                    $this->form_validation->set_rules('prices['.$i.'][description]', lang('text_description'), 'max_length[12000]|trim');
                    $this->form_validation->set_rules('prices['.$i.'][excerpt]', lang('text_excerpt'), 'max_length[32]|trim');
                    $this->form_validation->set_rules('prices['.$i.'][currency_id]', $i.lang('text_currency_id'), 'required|integer');
                    $this->form_validation->set_rules('prices['.$i.'][delivery_price]', lang('text_delivery_price'), 'required|numeric');
                    $this->form_validation->set_rules('prices['.$i.'][saleprice]', lang('text_saleprice'), 'numeric');
                    $this->form_validation->set_rules('prices['.$i.'][price]', lang('text_price'), 'required|numeric');
                    $this->form_validation->set_rules('prices['.$i.'][quantity]', lang('text_quantity'), 'integer');
                    $this->form_validation->set_rules('prices['.$i.'][supplier_id]', lang('text_supplier_id'), 'required|integer');
                    $this->form_validation->set_rules('prices['.$i.'][term]', lang('text_term'), 'integer');
                }
            }


            if ($this->form_validation->run() !== false){
                $file_name = $this->input->post('image');
                if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
                    $config['upload_path']          = './uploads/product/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['encrypt_name']         = true;

                    $this->load->library('upload', $config);

                    if($this->upload->do_upload('userfile')){
                        $upload_data = $this->upload->data();
                        $file_name = $upload_data['file_name'];
                    }
                    else{
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        redirect('autoxadmin/product/create');
                    }
                }
                $this->save_data(false, $file_name);
            }else{
                $this->error = validation_errors();
            }
        }

        $data['supplier'] = $this->supplier_model->supplier_get_all();
        $data['currency'] = $this->currency_model->currency_get_all();
        $data['category'] = $this->category_model->admin_category_get_all();

        $this->load->view('admin/header');
        $this->load->view('admin/product/create', $data);
        $this->load->view('admin/footer');
    }

    public function save_data($id = false, $file_name){

        $synonym = $this->synonym_model->get_synonyms();

        $product = [];
        $product['sku'] = $this->product_model->clear_sku($this->input->post('sku', true));
        $product['brand'] = $this->product_model->clear_brand($this->input->post('brand', true),$synonym);
        $product['name'] = $this->input->post('name', true);
        $product['image'] = $file_name;
        $product['h1'] = $this->input->post('h1', true);
        $product['title'] = $this->input->post('title', true);
        $product['meta_description'] = $this->input->post('meta_description', true);
        $product['meta_keywords'] = $this->input->post('meta_keywords', true);
        $product['viewed'] = (int)$this->input->post('viewed');
        $product['bought'] = (int)$this->input->post('bought');
        $product['category_id'] = (int)$this->input->post('category_id');
        $product['description'] = $this->input->post('description',true);
        if($this->input->post('slug')){
            $product['slug'] = $this->input->post('slug', true);
        }else{
            $product['slug'] = $this->product_model->getSlug($product);
        }

        $product_id = $this->product_model->insert($product,$id);


        if($product_id){
            $this->product_model->product_delete(['product_id' => (int)$product_id]);
            if($this->input->post('prices')){
                foreach ($this->input->post('prices') as $price){
                    if((int)$price['supplier_id'] > 0){
                        $save = [];
                        $save['product_id'] = (int)$product_id;
                        $save['supplier_id'] = (int)$price['supplier_id'];
                        $save['excerpt'] = $price['excerpt'];
                        $save['currency_id'] = (int)$price['currency_id'];
                        $save['delivery_price'] = (float)$price['delivery_price'];
                        $save['saleprice'] = (float)$price['saleprice'];
                        $save['price'] = (float)$price['price'];
                        $save['quantity'] = (int)$price['quantity'];
                        $save['term'] = (int)$price['term'];
                        $save['updated_at'] = date('Y-m-d H:i:s');
                        $save['status'] = (bool)$price['status'];
                        $this->product_model->table = 'product_price';
                        $this->product_model->insert($save);
                    }
                }
            }
            //Атрибуты к товару
            $this->product_attribute_model->delete($product_id);
            if($this->input->post('attributes') && $product['category_id']){
                foreach ($this->input->post('attributes') as $attribute){
                    $attributes_data[] = [
                        'product_id' => $product_id,
                        'attribute_name' =>trim($attribute['attribute_name']),
                        'attribute_value' =>trim($attribute['attribute_value']),
                        'category_id' => $product['category_id'],
                        'attribute_slug' => url_title($attribute['attribute_name'].' '.$attribute['attribute_value'])
                    ];
                }
                $this->product_attribute_model->insert_batch($attributes_data);
            }

        }
        $this->clear_cache();
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/product/edit/'.$product_id);
    }

    public function get_supplier_prices(){
        $supplier_id = (int)$this->input->post('supplier_id');
        $pricing = $this->pricing_model->get_by_supplier($supplier_id);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($pricing));
    }
}