<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Csv{
    public $CI;
    public $name = 'Csv format';

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function get_data($where){
        $this->CI->load->dbutil();
        $limit = 25000;
        $offset = $where['offset'];
        unset($where['offset']);
        $this->CI->db->select('
        product.sku, 
        product.brand, 
        product.name, 
       
        CONCAT("'.base_url().'product/",ax_product.slug) as url,
        saleprice,
        price,
        currency.name as cur_name,
        quantity,
        term,
        status as visible
        ', false);
        $this->CI->db->from('product');
        $this->CI->db->join('currency','currency.id=currency_id');
        $this->CI->db->limit($limit,$offset);
        foreach ($where as $field => $value){
            $this->CI->db->where($field,$value);
        }
        $query = $this->CI->db->get();
        if($query->num_rows() == 0 && $offset == 0){
            exit('<a href="'.base_url('autoxadmin/price').'">Home</a><br/>Empty results');
        }
        if($query->num_rows() > 0){
            if($offset == 0){
                file_put_contents('./uploads/price/csv/price.csv',$this->CI->dbutil->csv_from_result($query));
            }else{
                file_put_contents('./uploads/price/csv/price.csv',$this->CI->dbutil->csv_from_result($query),FILE_APPEND);
            }
            $where['offset'] = $offset + $limit;
            echo('<html>
                    <head>
                    <title>Export...</title>
                    </head>
                    <body>
                    Export...<br /><a id="go" href=\'/autoxadmin/price/get_data?library_name=Csv&where='.serialize($where).'\'>.</a>
                    <script type="text/javascript">document.getElementById(\'go\').click();</script>
                    </body>
                    </html>');
            die();
        }else{
            echo 'Link to price: <a href="'.base_url().'uploads/price/csv/price.csv">'.base_url().'uploads/price/csv/price.csv</a>';
            exit();
        }
    }

}
