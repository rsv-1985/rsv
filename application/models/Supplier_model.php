<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Supplier_model extends Default_model{
    public $table = 'supplier';
    public $suppliers;
    public $total_rows = 0;

    public function __construct()
    {
        $this->suppliers = $this->supplier_get_all();
    }

    public function get_suppliers($limit, $start){
        $this->db->select('SQL_CALC_FOUND_ROWS *', false);
        if($this->input->get('id')){
            $this->db->where('id',(int)$this->input->get('id'));
        }
        if($this->input->get('name')){
            $this->db->like('name',$this->input->get('name',true), 'both');
        }

        if ($limit && $start) {
            $this->db->limit((int)$limit, (int)$start);
        } elseif ($limit) {
            $this->db->limit((int)$limit);
        }

        if($this->input->get('sort')){
            $this->db->order_by($this->input->get('sort'),$this->input->get('order'));
        }else{
            $this->db->order_by('name','ASC');
        }

        $query = $this->db->get($this->table);

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
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