<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_pay_model extends Default_model
{
    public $total_rows;

    public $statuses = [0 => 'Ожидание', 1 => 'Подтвержден', 2 => 'Отменен'];

    public $table = 'customer_pay';

    public function customer_pay_get_all($limit, $start){
        $fields = $this->customer_pay_model->fields();

        $this->db->select('SQL_CALC_FOUND_ROWS cp.*, CONCAT_WS(" ", c.phone, c.first_name, c.second_name) as customer_name', false);
        $this->db->from('customer_pay cp');
        $this->db->join('customer c', 'c.id=cp.customer_id');
        $this->db->limit((int)$limit, (int)$start);


        if($this->input->get('id')){
            $this->db->where('cp.id', (int)$this->input->get('id'));
        }

        if(isset($_GET['status_id']) && $_GET['status_id'] != ''){
            $this->db->where('cp.status_id', (int)$this->input->get('status_id'));
        }

        if($this->input->get('amount')){
            $this->db->where('cp.amount', (float)$this->input->get('amount'));
        }

        if($this->input->get('customer_id')){
            $this->db->where('cp.customer_id', (int)$this->input->get('customer_id'));
        }

        if($this->input->get('transaction_date')){
            $this->db->where('DATE(cp.transaction_date)', $this->input->get('transaction_date'), true);
        }

        $order = ['ASC', 'DESC'];

        if(in_array($this->input->get('order'), $order)){
            $order = $this->input->get('order', true);
        }else{
            $order = 'DESC';
        }


        if(in_array($this->input->get('sort'), $fields)){
            $sort = $this->input->get('sort', true);
        }else{
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
}