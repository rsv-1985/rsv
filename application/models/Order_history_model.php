<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Order_history_model extends Default_model{
    public $table = 'order_history';

    public function history_get($id){
        $this->db->select("ax_order_history.*, CONCAT_WS(' ',ax_user.firstname, ax_user.lastname) as manager", true);
        $this->db->from($this->table);
        $this->db->join('user', 'user.id=order_history.user_id','left');
        $this->db->where('order_history.order_id',(int)$id);
        $this->db->order_by('date','DESC');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }
}