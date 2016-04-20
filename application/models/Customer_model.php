<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends Default_model{
    public $table = 'customer';

    public function is_login($redirect = false){
        if($this->session->customer_id){
            return $this->session->customer_id;
        }else{
            if($redirect){
                redirect($redirect);
            }
            return false;
        }
    }

    public function login($login, $password){
        $this->db->where('login',$login);
        $this->db->where('status', true);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $existingHashFromDb = $query->row_array()['password'];
            $isPasswordCorrect = password_verify($password, $existingHashFromDb);
            if($isPasswordCorrect){
                $newdata = array(
                    'customer_id'  => $query->row_array()['id'],
                    'customer_group_id'     => $query->row_array()['customer_group_id'],
                    'customer_name' => $query->row_array()['first_name']. ' ' . $query->row_array()['second_name']
                );
                $this->session->set_userdata($newdata);
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public function customer_count_all(){
        if($this->input->get()){
            if($this->input->get('login')){
                $this->db->like('login', $this->input->get('login', true));
            }
            if($this->input->get('customer_group_id')){
                $this->db->where('customer_group_id', $this->input->get('customer_group_id', true));
            }
            if($this->input->get('first_name')){
                $this->db->like('first_name', $this->input->get('first_name', true));
            }
            if($this->input->get('second_name')){
                $this->db->like('second_name', $this->input->get('second_name', true));
            }
            if($this->input->get('email')){
                $this->db->like('email', $this->input->get('email', true));
            }
            if($this->input->get('phone')){
                $this->db->like('phone', $this->input->get('phone', true));
            }
            if($this->input->get('status')){
                $this->db->where('status', $this->input->get('status', true));
            }
            return $this->db->count_all_results($this->table);
        }else{
            return $this->db->count_all($this->table);
        }
    }

    public function customer_get_all($limit = false, $start = false, $order_status = false){
        $this->db->select('*');
        //Получаем суммы заказов по покупателям
        if($order_status){
            foreach ($order_status as $status_id => $value){
                $this->db->select('(SELECT SUM(total) FROM ax_order WHERE customer_id = ax_customer.id AND status = "'.(int)$status_id.'") as sum_'.(int)$status_id);
            }
        }
       $this->db->from($this->table);
        if($this->input->get()){
            if($this->input->get('login')){
                $this->db->like('login', $this->input->get('login', true));
            }
            if($this->input->get('customer_group_id')){
                $this->db->where('customer_group_id', $this->input->get('customer_group_id', true));
            }
            if($this->input->get('first_name')){
                $this->db->like('first_name', $this->input->get('first_name', true));
            }
            if($this->input->get('second_name')){
                $this->db->like('second_name', $this->input->get('second_name', true));
            }
            if($this->input->get('email')){
                $this->db->like('email', $this->input->get('email', true));
            }
            if($this->input->get('phone')){
                $this->db->like('phone', $this->input->get('phone', true));
            }
            if($this->input->get('status')){
                $this->db->where('status', $this->input->get('status', true));
            }
        }

        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }
        $this->db->order_by('customer.id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}