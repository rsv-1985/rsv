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

    public function delete($id){

        $info = $this->customerbalance_model->get($id);

        if($info){

            if ($info['type'] == 1) {
                $type = 2;
            } else {
                $type = 1;
            }

            $this->customerbalance_model->add_transaction(
                $info['customer_id'],
                (float)$info['value'],
                'Отмена: ' . $info['description'],
                $type,
                false,
                $this->session->userdata('user_id')
            );

            //обнуляем привязки
            $this->db->where('id', $info['id']);
            $this->db->update($this->table, ['invoice_id' => 0, 'pay_id' => 0]);
        }
    }

    public function customerbalance_get_all($limit,$offset){
        $this->db->from($this->table);
        $this->db->select('SQL_CALC_FOUND_ROWS ax_customer_balance.*,CONCAT(ax_user.firstname," ",ax_user.lastname) as user,CONCAT_WS(" ", ax_customer.phone, ax_customer.first_name, ax_customer.second_name) as customer_name',false);
        $this->db->join('user','ax_user.id=ax_customer_balance.user_id','left');
        $this->db->join('customer','ax_customer.id=ax_customer_balance.customer_id','left');

        if($this->input->get('customer_id')){
            $this->db->where('ax_customer_balance.customer_id', (int)$this->input->get('customer_id'));
        }

        if($this->input->get('type')){
            $this->db->where('type',(int)$this->input->get('type'));
        }

        if($this->input->get('value')){
            $this->db->where('value',(float)$this->input->get('value'));
        }

        if($this->input->get('description')){
            $this->db->like('description',strip_tags($this->input->get('description',true)));
        }

        if($this->input->get('date_from')){
            $this->db->where('DATE(transaction_created_at) >=', $this->input->get('date_from', true));
        }

        if($this->input->get('date_to')){
            $this->db->where('DATE(transaction_created_at) <=', $this->input->get('date_to', true));
        }

        if($this->input->get('created_at')){
            $this->db->where('DATE(ax_customer_balance.transaction_created_at)',$this->input->get('created_at',true));
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

    public function add_transaction($customer_id, $value, $description = '', $type = 2, $transaction_created_at = '0000-00-00 00:00:00', $user_id = 0, $invoice_id = 0, $pay_id = 0){

        $customer_balance = (float)$this->customer_model->getBalance($customer_id);

        switch ($type){
            case 1:
                $balance =  (float)$customer_balance + (float)$value;
                break;
            case 2:
                $balance =  (float)$customer_balance - (float)$value;
                break;
        }

        $save['value'] = (float)$value;
        $save['type'] = $type;
        $save['description'] = (string)$description;
        $save['transaction_created_at'] = $transaction_created_at ? $transaction_created_at : date('Y-m-d H:i:s');
        $save['customer_id'] = (int)$customer_id;
        $save['user_id'] = (int)$user_id;
        $save['created_at'] = date("Y-m-d H:i:s");
        $save['balance'] = (float)$balance;
        $save['invoice_id'] = (int)$invoice_id;
        $save['pay_id'] = (int)$pay_id;

        $id = $this->db->insert($this->table,$save);

        if($id){
            //Обновляем баланс клиента
            $this->customer_model->insert(['balance' => (float)$balance], $save['customer_id']);
        }

        return (float)$balance;
    }

    public function getByInvoice($invoice_id){
        $this->db->from($this->table);
        $this->db->where('invoice_id', (int)$invoice_id);
        return $this->db->get()->row_array();
    }

    public function getByPay($pay_id){
        $this->db->from($this->table);
        $this->db->where('pay_id', (int)$pay_id);
        return $this->db->get()->row_array();
    }
}