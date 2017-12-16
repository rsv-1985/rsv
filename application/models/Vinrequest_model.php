<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vinrequest_model extends Default_model {
    public $table = 'vin';

    public $total_rows = 0;

    public function vin_get_all($limit, $start){
        $this->db->from('vin v');
        $this->db->select('SQL_CALC_FOUND_ROWS v.*, c.login, c.id as cid', false);
        $this->db->join('customer c', 'c.id=v.customer_id', 'left');

        if ($this->input->get('vin')) {
            $this->db->where('v.vin', $this->input->get('vin', true));
        }
        if ($this->input->get('name')) {
            $this->db->or_like('c.login', $this->input->get('name', true));
            $this->db->or_like('c.first_name', $this->input->get('name', true));
            $this->db->or_like('c.second_name', $this->input->get('name', true));
            $this->db->or_like('c.patronymic', $this->input->get('name', true));
        }
        $this->db->order_by('v.status','ASC');
        $this->db->order_by('v.id','DESC');

        $this->db->limit((int)$limit, (int)$start);

        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}