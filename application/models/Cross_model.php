<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Cross_model extends Default_model{
    public $table = 'cross';
    public $total_rows = 0;

    public function delete_by_filter(){
        if($this->input->get()){
            if($this->input->get('code')){
                $this->db->like('code',$this->input->get('code',true),'after');
            }

            if($this->input->get('code2')){
                $this->db->like('code2',$this->input->get('code2',true),'after');
            }

            if($this->input->get('brand')){
                $this->db->like('brand',$this->input->get('brand',true),'after');
            }

            if($this->input->get('brand2')){
                $this->db->like('brand2',$this->input->get('brand2',true),'after');
            }

            $this->db->delete('cross');
        }
    }

    public function cross_get_all($limit, $start){
        $this->db->select('SQL_CALC_FOUND_ROWS * FROM ax_cross', false);

        if($this->input->get('code')){
            $this->db->like('code',$this->input->get('code',true),'after');
        }

        if($this->input->get('code2')){
            $this->db->like('code2',$this->input->get('code',true),'after');
        }

        if($this->input->get('brand')){
            $this->db->like('brand',$this->input->get('brand',true),'after');
        }

        if($this->input->get('brand2')){
            $this->db->like('brand2',$this->input->get('brand2',true),'after');
        }


        $this->db->limit((int)$limit, (int)$start);

        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        return $query->result_array();

    }
}