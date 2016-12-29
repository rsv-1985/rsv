<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Pricing_model extends Default_model{
    public $table = 'pricing';

    public function get_by_supplier($id){
        return $this->db->where('supplier_id', (int)$id)->get('pricing')->result_array();
    }
}