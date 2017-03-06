<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sto extends Admin_controller {
    public function index(){
        $data = [];
        $this->load->view('admin/header');
        $this->load->view('admin/sto/sto', $data);
        $this->load->view('admin/footer');
    }

    public function settings(){
        $data = [];
        $this->load->view('admin/header');
        $this->load->view('admin/sto/settings', $data);
        $this->load->view('admin/footer');
    }
}