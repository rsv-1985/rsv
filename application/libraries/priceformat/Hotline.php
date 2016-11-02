<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotline{
    public $CI;
    public $name = 'Hotline csv';

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function get_data($where){
        $this->CI->load->dbutil();
        $limit = 25000;
        $offset = $where['offset'];
        unset($where['offset']);
        $this->CI->db->select("
        category.name as cname,
        product.brand,
        product.name,
        product.sku, 
        product.id as Id,
        product.description,
        product_price.price as price,
        currency.value as curs,
        price * value as final_price,
        'от производителя',
        product_price.quantity,
        0,
        CONCAT('".base_url()."product/',ax_product.slug) as url,
        product.image
        ", false);
        $this->CI->db->from('product_price');
        $this->CI->db->join('product','product.id=product_price.product_id');
        $this->CI->db->join('currency','currency.id=product_price.currency_id');
        $this->CI->db->join('category','category.id=product.category_id');
        $this->CI->db->group_by('product.sku');
        $this->CI->db->order_by('final_price','ASC');
        $this->CI->db->limit($limit,$offset);
        foreach ($where as $field => $value){
            $this->CI->db->where($field,$value);
        }
        $query = $this->CI->db->get();
        if($query->num_rows() == 0 && $offset == 0){
            exit('<a href="'.base_url('autoxadmin/price').'">Home</a><br/>Empty results');
        }
        if($query->num_rows() > 0){
            $content = '';
            $results = $query->result_array();
            foreach ($results as $result){
                unset($result['price']);
                unset($result['curs']);
                $result['quantity'] = $result['quantity'] > 0 ? 'в наличии' : 'под заказ';
                $result['image'] = $result['image'] ? base_url('uploads/product/'.$result['image']) : '';
                $content .= implode(';',$result).PHP_EOL;
            }
            if($offset == 0){
                file_put_contents('./uploads/price/hotline/price.csv',$content);
            }else{
                file_put_contents('./uploads/price/hotline/price.csv',$content,FILE_APPEND);
            }

            $where['offset'] = $offset + $limit;
            echo('<html>
                    <head>
                    <title>Export...</title>
                    </head>
                    <body>
                    Export...<br /><a id="go" href=\'/autoxadmin/price/get_data?library_name=Hotline&where='.serialize($where).'\'>.</a>
                    <script type="text/javascript">document.getElementById(\'go\').click();</script>
                    </body>
                    </html>');
            die();
        }else{
            echo 'Link to price: <a href="'.base_url().'uploads/price/hotline/price.csv">'.base_url().'uploads/price/hotline/price.csv</a>';
            exit();
        }
    }

}
