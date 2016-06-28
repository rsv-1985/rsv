<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('class-dpdfrance.php');
class Dpd{
    public $CI;
    public $delivery_price = 0;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function get_form(){
        $this->delivery_price = 100;
    }

    public function get_comment(){

    }
}