<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Blacklist extends Admin_controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('black_list_model');
    }

    public function add(){
        $this->black_list_model->insert(['customer_id' => (int)$this->input->post('customer_id'), 'comment' => (string)$this->input->post('comment',true)]);
    }

    public function delete(){
        $this->black_list_model->delete((int)$this->input->post('customer_id'));
    }

}