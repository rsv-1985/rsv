<?php
require(APPPATH . 'libraries/REST_Controller.php');
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

    public function index_get()
    {
        $synonyms = $this->synonym_model->get_synonyms();

        $brand = $this->product_model->clear_brand($this->input->get('brand', true), $synonyms);

        $sku = $this->product_model->clear_sku($this->input->get('sku', true));

        $ID_art = $this->tecdoc->getIDart($sku, $brand);

        $with_cross = (bool)$this->input->get('with_cross');

        $customer_info = $this->customer_model->get($this->rest->user_id);

        $this->customergroup_model->customer_group = $this->customergroup_model->get($customer_info['customer_group_id']);

        $results['products'] = false;
        $results['cross'] = false;

        $response = false;

        if ($sku && $brand) {
            $crosses_search = false;
            if ($with_cross) {
                $crosses_search = $this->product_model->get_crosses($ID_art[0]->ID_art, $brand, $sku);
            }

            //Делаем поиск по апи поставщиков c возвратом крос номеров от поставщика
            $cross_suppliers = $this->product_model->api_supplier($sku, $brand, $crosses_search);

            if ($cross_suppliers) {
                foreach ($cross_suppliers as $cross_supplier) {
                    $crosses_search = array_merge($crosses_search, $cross_supplier);
                }
                $crosses_search = array_unique($crosses_search, SORT_REGULAR);
            }

            $product = $this->product_model->get_search_products($sku, $brand);

            $crosses = false;
            if ($with_cross && $crosses_search) {
                $crosses = $this->product_model->get_search_crosses($crosses_search);
            }


            if ($product && $product['prices']) {
                foreach ($product['prices'] as $item) {
                    $response[] = [
                        'id' => $item['product_id'],
                        'supplier_id' => $item['supplier_id'],
                        'sku' => $product['sku'],
                        'brand' => $product['brand'],
                        'name' => $product['name'],
                        'href' => base_url('product/' . $product['slug']),
                        'excerpt' => $item['excerpt'],
                        'description' => $product['description'],
                        'price' => $item['saleprice'] > 0 ? $item['saleprice'] : $item['price'],
                        'quantity' => $item['quantity'],
                        'term' => $item['term'],
                        'cross' => false
                    ];
                }
            }
            if ($crosses) {
               foreach ($crosses as $cross){
                   if($cross['prices']){
                       foreach ($cross['prices'] as $item) {
                           $response[] = [
                               'id' => $item['product_id'],
                               'supplier_id' =>$item['supplier_id'],
                               'sku' => $cross['sku'],
                               'brand' => $cross['brand'],
                               'name' => $cross['name'],
                               'href' => base_url('product/' . $cross['slug']),
                               'excerpt' => $item['excerpt'],
                               'description' => $cross['description'],
                               'price' => $item['saleprice'] > 0 ? $item['saleprice'] : $item['price'],
                               'quantity' => $item['quantity'],
                               'term' => $item['term'],
                               'cross' => true
                           ];
                       }
                   }
               }
            }
        }

        $this->response($response);
    }

}