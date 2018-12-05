<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Sto_model extends Default_model{
    public $table = 'sto';

    public function get_sto($date){
        $this->db->select('sto.*, sto_status.name as status, sto_service.name as service');
        $this->db->where('DATE(date)',$date);
        $this->db->order_by('date','ASC');
        $this->db->join('sto_status', 'sto_status.id=sto.status_id');
        $this->db->join('sto_service', 'sto_service.id=sto.service_id');
        $query = $this->db->get('sto');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function sto_get_all($limit, $start){
        $this->db->select('sto.*, sto_status.name as status, sto_service.name as service');
        $this->db->join('sto_status', 'sto_status.id=sto.status_id');
        $this->db->join('sto_service', 'sto_service.id=sto.service_id');
        $this->db->limit($limit,$start);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('sto');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }



    public function addService($data, $id = false){
        if($id){
            $this->db->where('id', (int)$id);
            $this->db->update('sto_service', $data);
            return $id;
        }else{
            $this->db->insert('sto_service', $data);
            return $this->db->insert_id();
        }
    }

    public function addStatus($data, $id = false){
        if($id){
            $this->db->where('id', (int)$id);
            $this->db->update('sto_status', $data);
            return $id;
        }else{
            $this->db->insert('sto_status', $data);
            return $this->db->insert_id();
        }
    }

    public function getServices(){
        $return = [];
        $this->db->order_by('sort_order', 'ASC');
        $results = $this->db->get('sto_service')->result_array();
        if($results){
            foreach ($results as $item){
                $return[$item['id']] = $item;
            }
        }
        return $return;
    }

    public function deleteService($id){
        $this->db->where('id', (int)$id);
        $this->db->delete('sto_service');
    }

    public function getService($id){
        $this->db->where('id', (int)$id);
        return $this->db->get('sto_service')->row_array();
    }

    public function getStatuses(){
        $return = [];
        $this->db->order_by('sort_order', 'ASC');
        $result = $this->db->get('sto_status')->result_array();
        foreach ($result as $item){
            $return[$item['id']] = $item;
        }

        return $return;
    }

    public function deleteStatus($id){
        $this->db->where('id', (int)$id);
        $this->db->delete('sto_status');
    }

    public function getStatus($id){
        $this->db->where('id', (int)$id);
        return $this->db->get('sto_status')->row_array();
    }
}