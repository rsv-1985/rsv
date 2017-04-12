<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_loader extends CI_Loader{
    public function view($view, $vars = array(), $return = FALSE)
    {
        $CI = &get_instance();
        return $this->_ci_load(array('_ci_view' => 'themes/'.$CI->config->item('theme').'/'.$view, '_ci_vars' => $this->_ci_prepare_view_vars($vars), '_ci_return' => $return));
    }
}