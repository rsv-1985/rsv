<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Canonical_model extends Default_model{
    public $table = 'canonical';

    public function getByUri($uri){
        $this->db->where('url',$uri);
        return $this->db->get($this->table)->row_array();
    }
}