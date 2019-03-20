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
        $this->load->model('admin/mcategory');
        $this->load->model('supplier_model');
        $this->load->model('pricing_model');
        $this->load->model('currency_model');
        $this->load->model('synonym_model');
        $this->load->model('admin/mproduct');
        $this->load->model('admin/mattribute');
    }

    public function index(){
        $data = [];

        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/product/index');

        $config['per_page'] = 10;
        $data['products'] = $this->product_model->admin_product_get_all($config['per_page'], $this->uri->segment(4));

        $config['total_rows'] = $this->product_model->admin_product_get_all(false, false, true);
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        if($this->input->post()){
            $this->form_validation->set_rules('delivery_price', lang('text_delivery_price'), 'required|numeric|trim');
            $this->form_validation->set_rules('price', lang('text_price'), 'numeric|trim');
            $this->form_validation->set_rules('saleprice', lang('text_saleprice'), 'numeric|trim');
            if ($this->form_validation->run() !== false){
                $save = [];
                $save['delivery_price'] = (float)$this->input->post('delivery_price', true);
                $save['price'] = (float)$this->input->post('price', true);
                $save['saleprice'] = (float)$this->input->post('saleprice', true);

                $product_id = (int)$this->input->post('product_id');
                $supplier_id = (int)$this->input->post('supplier_id');
                $term = (int)$this->input->post('term');
                $this->product_model->update_item($save, $product_id, $supplier_id, $term);
                $this->db->where('id',(int)$product_id)->set('name',$this->input->post('name', true))->update('product');
                $this->session->set_flashdata('success', lang('text_success'));
            }else{
                $this->session->set_flashdata('error', validation_errors());
            }
            redirect($_SERVER['HTTP_REFERER']);
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

        $data['tecdoc_info'] = false;
        $ID_art = $this->tecdoc->getIDart($data['product']['sku'],$data['product']['brand']);
        if($ID_art){
            $data['tecdoc_info'] = true;
        }

        $data['attributes'] = $this->mattribute->getAttributes();

        $data['prices'] = $this->product_model->get_product_price($data['product'],false);

        $data['product_attributes'] = $this->mproduct->getAttributes($id);

        $data['images'] = $this->product_model->getProductImages($id);

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
                    $this->form_validation->set_rules('prices['.$i.'][description]', lang('text_description'), 'trim');
                    $this->form_validation->set_rules('prices['.$i.'][currency_id]', $i.lang('text_currency_id'), 'required|integer');
                    $this->form_validation->set_rules('prices['.$i.'][delivery_price]', lang('text_delivery_price'), 'required|numeric');
                    $this->form_validation->set_rules('prices['.$i.'][saleprice]', lang('text_saleprice'), 'numeric');
                    $this->form_validation->set_rules('prices['.$i.'][price]', lang('text_price'), 'numeric');
                    $this->form_validation->set_rules('prices['.$i.'][quantity]', lang('text_quantity'), 'integer');
                    $this->form_validation->set_rules('prices['.$i.'][supplier_id]', lang('text_supplier_id'), 'required|integer');
                    $this->form_validation->set_rules('prices['.$i.'][term]', lang('text_term'), 'integer');
                }
            }




            if ($this->form_validation->run() !== false){
                $file_name = $this->input->post('image');
                if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
                    $config['upload_path']          = './uploads/product/';
                    $config['allowed_types']        = 'gif|jpg|jpeg|png';
                    $config['encrypt_name']         = true;

                    $this->load->library('upload', $config);

                    if($this->upload->do_upload('userfile')){
                        $upload_data = $this->upload->data();
                        $file_name = $upload_data['file_name'];
                        @unlink('./uploads/product/'.$data['product']['image']);
                    }
                    else{
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        redirect('autoxadmin/product/edit/'.$id);
                    }
                }else{
                    if(mb_strlen($file_name) == 0){
                        @unlink('./uploads/product/'.$data['product']['image']);
                    }
                }

                if(isset($_FILES['userfile2'])){
                    $images = $this->upload_files();
                }

                $this->save_data($id, $file_name, $images);
            }else{
                $this->error = validation_errors();
            }
        }
        $data['supplier'] = $this->supplier_model->supplier_get_all();
        $data['currency'] = $this->currency_model->currency_get_all();
        $data['categories'] = $this->mcategory->getCategories();

        $this->load->view('admin/header');
        $this->load->view('admin/product/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete_product($id){
        $this->db->where('id',(int)$id)->delete('product');
        $this->db->where('product_id',(int)$id)->delete('product_price');
        $this->db->where('product_id',(int)$id)->delete('product_attributes');
        $this->session->set_flashdata('success', lang('text_success'));
        $this->clear_cache();
        redirect('autoxadmin/product');
    }

    public function delete_product_carts(){
        $this->db->query('DELETE FROM `ax_product_price` WHERE supplier_id = 0');
        $this->db->query('DELETE FROM ax_product WHERE id NOT IN (SELECT DISTINCT product_id FROM ax_product_price)');
        $this->db->query("DELETE FROM ax_product_attributes WHERE product_id NOT IN (SELECT id FROM ax_product)");
        $this->session->set_flashdata('success', lang('text_success'));
        $this->clear_cache();
        redirect('autoxadmin/product');
    }

    public function delete_by_filter(){
        $delete_product_card = (int)$this->input->get('delete_product_card');
        $where = '';
        if($this->input->post('sku')){
            $where .= " AND p.sku = ".$this->db->escape($this->input->post('sku', true));
        }

        if($this->input->post('brand')){
            $where .= " AND p.brand = ".$this->db->escape($this->input->post('brand', true));
        }

        if($this->input->post('name')){
            $where .= " AND p.name LIKE '%".$this->db->escape_like_str($this->input->post('brand', true))."%'";
        }

        if($this->input->post('supplier_id')){
            $where .= " AND pp.supplier_id = ".$this->db->escape($this->input->post('supplier_id', true));
        }

        if($delete_product_card == 1){
            $this->db->query("DELETE p FROM ax_product p LEFT JOIN ax_product_price pp ON pp.product_id = p.id WHERE 1 ".$where);

            $this->db->query("DELETE FROM ax_product_price WHERE product_id NOT IN (SELECT id FROM ax_product)");

            $this->db->query("DELETE FROM ax_product_attributes WHERE product_id NOT IN (SELECT id FROM ax_product)");

        }else{
            $this->db->query("DELETE pp FROM ax_product_price pp LEFT JOIN ax_product p ON p.id = pp.product_id WHERE 1 ".$where);
        }


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
                    $this->form_validation->set_rules('prices['.$i.'][description]', lang('text_description'), 'trim');
                    $this->form_validation->set_rules('prices['.$i.'][excerpt]', lang('text_excerpt'), 'max_length[125]|trim');
                    $this->form_validation->set_rules('prices['.$i.'][currency_id]', $i.lang('text_currency_id'), 'required|integer');
                    $this->form_validation->set_rules('prices['.$i.'][delivery_price]', lang('text_delivery_price'), 'required|numeric');
                    $this->form_validation->set_rules('prices['.$i.'][saleprice]', lang('text_saleprice'), 'numeric');
                    $this->form_validation->set_rules('prices['.$i.'][price]', lang('text_price'), 'numeric');
                    $this->form_validation->set_rules('prices['.$i.'][quantity]', lang('text_quantity'), 'integer');
                    $this->form_validation->set_rules('prices['.$i.'][supplier_id]', lang('text_supplier_id'), 'required|integer');
                    $this->form_validation->set_rules('prices['.$i.'][term]', lang('text_term'), 'integer');
                }
            }


            if ($this->form_validation->run() !== false){
                if($check_product = $this->db->where('sku',$this->input->post('sku',true))->where('brand',$this->input->post('brand',true))->get('product')->row_array()){
                    $this->session->set_flashdata('error', sprintf(lang('error_duplicate_sku'),$check_product['id']));
                    redirect('autoxadmin/product/create');
                }
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
        $data['categories'] = $this->mcategory->getCategories();
        $data['attributes'] = $this->mattribute->getAttributes();

        $this->load->view('admin/header');
        $this->load->view('admin/product/create', $data);
        $this->load->view('admin/footer');
    }

    public function save_data($id = false, $file_name, $images = false){

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
        $product['description'] = $this->input->post('description');
        $product['static_price'] = (float)$this->input->post('static_price');
        $product['static_currency_id'] = (int)$this->input->post('static_currency_id');
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
                        $save['quantity'] = (int)$price['quantity'];
                        $save['term'] = (int)$price['term'];
                        $save['updated_at'] = date('Y-m-d H:i:s');
                        $save['price'] = (float)$price['price'];
                        $this->product_model->table = 'product_price';

                        $this->product_model->insert($save);
                    }
                }
            }
            //Атрибуты к товару
            $this->mproduct->deleteAttributes($product_id);
            if($this->input->post('attributes')){
                foreach ($this->input->post('attributes') as $attribute){
                    $attributes_data[] = [
                        'product_id' => $product_id,
                        'attribute_id' => (int)$attribute['attribute_id'],
                        'attribute_value_id' => (int)$attribute['attribute_value_id'],
                    ];
                }
                $this->mproduct->addAttributesBatch($attributes_data);
            }

            if($images){
                foreach ($images as $image){
                    $save = [
                        'product_id' => (int)$product_id,
                        'image' => $image
                    ];

                    $this->db->insert('product_images', $save);
                }
            }
        }
        $this->clear_cache();
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/product/edit/'.$product_id);
    }

    public function get_attribute_values($attribute_id){
        $html = '';
        $values = $this->mattribute->getValues($attribute_id);
        if($values){
            foreach ($values as $value){
                $html .= '<option value="'.$value['id'].'">'.$value['value'].'</option>';
            }
        }
        exit($html);
    }



    public function delete_image(){
        $image_id = $this->input->get('image_id');
        $image_info = $this->db->where('id',$image_id)->get('product_images')->row_array();
        if($image_info){
            $path = './uploads/product/'.$image_info['image'];
            if(file_exists('./uploads/product/'.$image_info['image'])){
                unlink($path);
            }

            $this->db->where('id',$image_id)->delete('product_images');
        }
    }

    private function upload_files()
    {
        $images = [];

        $config['upload_path']          = './uploads/product';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['encrypt_name']         = true;

        $this->load->library('upload', $config);

        foreach ($_FILES['userfile2']['name'] as $key => $image) {
            $_FILES['images']['name']= $_FILES['userfile2']['name'][$key];
            $_FILES['images']['type']= $_FILES['userfile2']['type'][$key];
            $_FILES['images']['tmp_name']= $_FILES['userfile2']['tmp_name'][$key];
            $_FILES['images']['error']= $_FILES['userfile2']['error'][$key];
            $_FILES['images']['size']= $_FILES['userfile2']['size'][$key];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('images')) {
                $upload_data = $this->upload->data();
                $images[] = $upload_data['file_name'];
            }
        }

        return $images;
    }
}