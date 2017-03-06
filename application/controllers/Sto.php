<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sto extends Front_controller {
    public function index(){
        $data = [];

        $this->load->view('header');
        $this->load->view('sto/sto', $data);
        $this->load->view('footer');
    }
}