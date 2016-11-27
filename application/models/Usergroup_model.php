<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Usergroup_model extends Default_model
{
    public $table = 'user_group';

    public function get_controllers_name()
    {
        $this->load->helper('file');
        $return = [];
        $controllers = get_filenames('./application/controllers/autoxadmin/');
        foreach ($controllers as $controller) {
            $str = strpos($controller, ".");
            $class_name = strtolower(substr($controller, 0, $str));
            if($class_name != 'index' && $class_name != 'admin'){
                $this->load->language('admin/'.$class_name);
                $return[$class_name] = $this->lang->line('text_heading', FALSE);
            }
        }
        return $return;
    }

    public function get_access($group_id){
        $result = $this->get($group_id);
        if($result){
            return unserialize($result['access']);
        }
        return false;
    }
}