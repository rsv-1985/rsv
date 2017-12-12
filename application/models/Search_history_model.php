<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Search_history_model extends Default_model{
    public $table = 'search_history';

    public $total_rows = 0;

    public function search_history_get_all($limit, $start){
        $this->db->from('search_history sh');
        $this->db->select('SQL_CALC_FOUND_ROWS sh.*, c.login, c.id as cid', false);
        $this->db->join('customer c', 'c.id=sh.customer_id', 'left');

        if ($this->input->get('sku')) {
            $this->db->where('sh.sku', $this->input->get('sku', true));
        }
        if ($this->input->get('brand')) {
            $this->db->where('sh.brand', $this->input->get('brand', true));
        }
        if ($this->input->get('name')) {
            $this->db->or_like('c.login', $this->input->get('name', true));
            $this->db->or_like('c.first_name', $this->input->get('name', true));
            $this->db->or_like('c.second_name', $this->input->get('name', true));
            $this->db->or_like('c.patronymic', $this->input->get('name', true));
        }

        $this->db->order_by('sh.id','DESC');

        $this->db->limit((int)$limit, (int)$start);

        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function search_history_customer($limit, $start, $customer_id){
        $this->db->from('search_history sh');
        $this->db->select('SQL_CALC_FOUND_ROWS *', false);
        $this->db->where('customer_id',(int)$customer_id);

        $this->db->order_by('sh.id','DESC');

        $this->db->limit((int)$limit, (int)$start);

        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}