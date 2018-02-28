<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Customer_group_pricing_model extends Default_model{
    public $table = 'customer_group_pricing';

    public $pricing;

    public function __construct()
    {
        //Если покупатель залогинен, получаем ценообразование по группу покупателя
        if($this->customergroup_model->customer_group){
            $this->pricing = $this->get_customer_group_pricing($this->customergroup_model->customer_group['id']);
        }
    }

    public function get_customer_group_pricing($customer_group_id){
        return $this->db->where('customer_group_id',(int)$customer_group_id)->order_by('brand','DESC')->get($this->table)->result_array();
    }

    public function delete_by_customer_group($customer_group_id){
        $this->db->where('customer_group_id',(int)$customer_group_id)->delete($this->table);
    }
}