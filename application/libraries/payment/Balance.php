<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance{
    public $CI;
    public function __construct()
    {
        $this->CI = get_instance();
    }

    public function get_form($order_id){
        exit($order_id);
    }
}