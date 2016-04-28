<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Settings_model extends Default_model{
    public $table = 'settings';

    public function get_by_key($key){
        $this->db->select('value');
        $this->db->where('key_settings', $key);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return unserialize($query->row_array()['value']);
        }
        return false;
    }

    public function get_by_group($key){
        $this->db->where('group_settings', $key);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $return = [];
            foreach($query->result_array() as $item){
                $return[$item['key_settings']] = unserialize($item['value']);
            }
            return $return;
        }
        return false;
    }

    public function add($data){
        $this->db->insert($this->table, $data);
    }
}