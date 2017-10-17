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

    public function add_transaction($customer_id, $value, $description = '', $type = 2, $transaction_created_at = '0000-00-00 00:00:00', $user_id = 0){
        $customer_balance = (float)$this->customer_model->getBalance($customer_id);

        $save['value'] = (float)$value;
        $save['type'] = $type;
        $save['description'] = (string)$description;
        $save['transaction_created_at'] = $transaction_created_at ? $transaction_created_at : date('Y-m-d H:i:s');
        $save['customer_id'] = (int)$customer_id;
        $save['user_id'] = (int)$user_id;
        $save['created_at'] = date("Y-m-d H:i:s");
        $this->db->insert($this->table,$save);

        if ($save['type'] == 1) {
            $save2['balance'] = $customer_balance + $save['value'];
        } else {
            $save2['balance'] = $customer_balance - $save['value'];
        }

        $this->customer_model->insert($save2, $save['customer_id']);

        return $save2['balance'];

    }
}