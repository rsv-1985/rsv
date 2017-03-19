<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Sto_model extends Default_model{
    public $table = 'sto';

    public function get_sto($date){
        $this->db->where('date',$date);
        $this->db->order_by('time','ASC');
        $query = $this->db->get('sto');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function sto_get_all($limit, $start){
        $this->db->limit($limit,$start);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('sto');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }
}