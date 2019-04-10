<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Msupplier_pay extends Default_model
{
    public $table = 'supplier_pay';
    public $total_rows = 0;

    public function getPays($limit, $start)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS sp.*, s.name as supplier_name', false);
        $this->db->from('supplier_pay sp');
        $this->db->join('supplier s','s.id=sp.supplier_id', 'left');

        if ($this->input->get('id')) {
            $this->db->where('sp.id', (int)$this->input->get('id'));
        }

        if ($this->input->get('supplier_id')) {
            $this->db->where('supplier_id', (int)$this->input->get('supplier_id'));
        }

        if ($this->input->get('date_from')) {
            $this->db->where("DATE(transaction_date) >= ", $this->input->get('date_from', true));
        }

        if ($this->input->get('date_to')) {
            $this->db->where("DATE(transaction_date) <= ", $this->input->get('date_to', true));
        }

        if ($this->input->get('comment')) {
            $this->db->like("comment", $this->input->get('comment', true));
        }

        if ($this->input->get('amount')) {
            $this->db->where("amount", (float)$this->input->get('amount', true));
        }

        $order = ['ASC', 'DESC'];

        if (in_array($this->input->get('order'), $order)) {
            $order = $this->input->get('order', true);
        } else {
            $order = 'DESC';
        }

        $fields = $this->fields();
        if (in_array($this->input->get('sort'), $fields)) {
            $sort = $this->input->get('sort', true);
        } else {
            $sort = 'id';
        }

        $this->db->order_by($sort, $order);

        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function getPay($id){
        return $this->db->where('id', (int)$id)->get($this->table)->row_array();
    }
}