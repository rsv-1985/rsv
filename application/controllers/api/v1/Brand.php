<?php
require(APPPATH . 'libraries/REST_Controller.php');
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
    }

    public function index_get()
    {
        $response = $this->product_model->get_brands($this->input->get('sku', true));
        $this->response($response);
    }

}