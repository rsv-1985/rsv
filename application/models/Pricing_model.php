<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Pricing_model extends Default_model{
    public $table = 'pricing';

    public $pricing;

    public function __construct()
    {
        $this->pricing = $this->pricing_get_all();
    }

    public function pricing_get_all(){
        $pricing = false;
        $this->db->order_by('brand','DESC');
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $item){
                $pricing[$item['supplier_id']][]=$item;
            }
        }
        return $pricing;
    }

    public function get_by_supplier($id){
        return
            $this->db->where('supplier_id', (int)$id)->order_by('price_from','ASC')->order_by('brand','DESC')->get('pricing')->result_array();
    }
}