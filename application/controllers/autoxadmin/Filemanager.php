<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Apikey extends Admin_controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');

    }
}
