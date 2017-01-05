<?php
require(APPPATH.'libraries/REST_Controller.php');
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('synonym_model');
        $this->load->model('customergroup_model');
        $this->load->model('customer_model');
    }

    public function  index_get()
    {
        $synonyms = $this->synonym_model->get_synonyms();

        $brand = $this->product_model->clear_brand($this->input->get('brand',true),$synonyms);

        $sku = $this->product_model->clear_sku($this->input->get('sku',true));

        $ID_art = $this->tecdoc->getIDart($sku, $brand);

        $with_cross = (bool)$this->input->get('with_cross');

        $customer_info = $this->customer_model->get($this->rest->user_id);

        $product_model = new Product_model();
        $product_model->customer_group = $this->customergroup_model->get($customer_info['customer_group_id']);

        $results = $product_model->get_search($ID_art,$brand,$sku,$with_cross);

        $response = false;

        if($results){
            if($results['products']){
                foreach ($results['products'] as $product){
                    $response[] = [
                        'id' => $product['product_id'],
                        'supplier_id' => $product['supplier_id'],
                        'sku' => $product['sku'],
                        'brand' => $product['brand'],
                        'name' => $product['name'],
                        'href' => base_url('product/'.$product['slug']),
                        'excerpt' => $product['excerpt'],
                        'description' => $product['description'],
                        'price' => $product['saleprice'] > 0 ? $product['saleprice'] : $product['price'],
                        'quantity' => $product['quantity'],
                        'term' => $product['term'],
                        'cross' => false
                    ];
                }
            }
            if($results['cross']){
                foreach ($results['cross'] as $product){
                    $response[] = [
                        'id' => $product['product_id'],
                        'sku' => $product['sku'],
                        'brand' => $product['brand'],
                        'name' => $product['name'],
                        'href' => base_url('product/'.$product['slug']),
                        'excerpt' => $product['excerpt'],
                        'description' => $product['description'],
                        'price' => $product['saleprice'] > 0 ? $product['saleprice'] : $product['price'],
                        'quantity' => $product['quantity'],
                        'term' => $product['term'],
                        'cross' => true
                    ];
                }
            }
        }

        $this->response($response);
    }

}