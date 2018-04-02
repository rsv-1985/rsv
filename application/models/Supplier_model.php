<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Supplier_model extends Default_model{
    public $table = 'supplier';
    public $suppliers;

    public function __construct()
    {
        $this->suppliers = $this->supplier_get_all();
    }

    public function supplier_get_all(){
        $results = $this->db->order_by('name','ASC')->get($this->table)->result_array();
        $return = [];
        foreach($results as $result){
            $return[$result['id']] = $result;
        }
        return $return;
    }
}