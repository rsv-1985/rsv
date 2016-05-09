<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Newsletter_model extends Default_model{
    public $table = 'newsletter';

    public function newsletter_count_all(){
        if($this->input->get()){
            if($this->input->get('email')){
                $this->db->like('email', $this->input->get('email', true));
            }
            return $this->db->count_all_results($this->table);
        }else{
            return $this->db->count_all($this->table);
        }
    }

    public function newsletter_get_all($limit, $start){
        if($this->input->get()){
            if($this->input->get('email')){
                $this->db->like('email', $this->input->get('email', true));
            }
        }

        $this->db->limit((int)$limit, (int)$start);

        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function export_csv(){
        $this->load->dbutil();
        $query = $this->db->query("SELECT * FROM ax_newsletter");
        $delimiter = ",";
        $newline = "\r\n";
        $enclosure = '"';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        fwrite($output, $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure));
    }
}