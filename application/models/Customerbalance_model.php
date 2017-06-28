<?php

/**
 * Created by PhpStorm.
 * User: serg
 * Date: 05.06.2017
 * Time: 13:11
 */
class Customerbalance_model extends Default_model
{
    public $total_rows;

    public $types = ['1' => 'Дебет','2' => 'Кредит'];

    public $table = 'customer_balance';

    public function customerbalance_get_all($limit,$offset){
        $this->db->from($this->table);
        $this->db->select('SQL_CALC_FOUND_ROWS ax_customer_balance.*,ax_customer.login,ax_customer.balance,CONCAT(ax_user.firstname," ",ax_user.lastname) as user',false);
        $this->db->join('user','ax_user.id=ax_customer_balance.user_id','left');
        $this->db->join('customer','ax_customer.id=ax_customer_balance.customer_id','left');
        if($this->input->get('type')){
            $this->db->where('type',(int)$this->input->get('type'));
        }

        if($this->input->get('value')){
            $this->db->where('value',(float)$this->input->get('value'));
        }

        if($this->input->get('description')){
            $this->db->like('description',strip_tags($this->input->get('description',true)));
        }

        if($this->input->get('login')){
            $this->db->where('login',strip_tags($this->input->get('login',true)));
        }

        if($this->input->get('created_at')){
            $this->db->where('DATE(ax_customer_balance.transaction_created_at)',strip_tags($this->input->get('created_at',true)),true);
        }

        $this->db->limit($limit,$offset);
        $this->db->order_by('id','DESC');
        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if($query->num_rows() > 0){
            return $query->result_array();
        }

        return false;
    }

    public function get_customer_balance($customer_id){

    }
}