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

    public function get_price_settings(){
        $html = '<h4>Условия отбора</h4>';

        $this->CI->load->model('category_model');
        $categories = $this->CI->category_model->admin_category_get_all();

        if($categories){
            $html .= '<div class="form-group"><label>Категория</label>';
            $html .= '<select name="category_id" class="form-control">';
            $html .= '<option></option>';
            foreach ($categories as $category){
                $html .= '<option value="'.$category['id'].'">'.$category['name'].'</option>';
            }
            $html .= '</select>';
            $html .= '</div>';
        }

        $this->CI->load->model('supplier_model');
        $suppliers = $this->CI->supplier_model->supplier_get_all();

        if($suppliers){
            $html .= '<div class="form-group"><label>Поставщик</label>';
            $html .= '<select name="supplier_id" class="form-control">';
            $html .= '<option></option>';
            foreach ($suppliers as $supplier){
                $html .= '<option value="'.$supplier['id'].'">'.$supplier['name'].'</option>';
            }
            $html .= '</select>';
            $html .= '</div>';
        }

        $html .= '<div class="form-group"><label>Акционный</label>';
        $html .= '<select name="saleprice" class="form-control">';
        $html .= '<option></option>';
        $html .= '<option value="1">Да</option>';
        $html .= '<option value="0">Нет</option>';
        $html .= '</select>';
        $html .= '</div>';

        $html .= '<div class="form-group"><label>Статус товара</label>';
        $html .= '<select name="status" class="form-control">';
        $html .= '<option></option>';
        $html .= '<option value="1">Отображается</option>';
        $html .= '<option value="0">Скрыто</option>';
        $html .= '</select>';
        $html .= '</div>';

        $html .= '<div class="form-group"><label>Бренд</label>';
        $html .= '<textarea name="brand" class="form-control" placeholder="BOSCH,DEPO,MAHLE"></textarea>';
        $html .= '</div>';

        $html .= '<div class="form-group"><label>Исключить бренды</label>';
        $html .= '<textarea name="exclude_brand" class="form-control" placeholder="BOSCH,DEPO,MAHLE"></textarea>';
        $html .= '</div>';

        $html .= '<div class="form-group"><label>Цена от</label>';
        $html .= '<input type="text" name="price_from" class="form-control" placeholder="10.5">';
        $html .= '</div>';

        $html .= '<div class="form-group"><label>Цена до</label>';
        $html .= '<input type="text" name="price_to" class="form-control" placeholder="99">';
        $html .= '</div>';


        $html .= '<div class="form-group"><label>Срок доставки в часах от</label>';
        $html .= '<input type="text" name="term_from" class="form-control" placeholder="24">';
        $html .= '</div>';

        $html .= '<div class="form-group"><label>Срок доставки в часах до</label>';
        $html .= '<input type="text" name="term_to" class="form-control" placeholder="36">';
        $html .= '</div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" name="unique" value="1">Уникальное предложение по минимальной цене</label>';
        $html .= '</div></div>';

        $html .= '<h4>Доп настройки</h4>';
        $html .= '<div class="form-group"><label>Наценка к цене поставки (новая цена сайта)</label>';
        $html .= '<input type="text" name="margin" class="form-control" placeholder="0-100:50;100-99999:40">';
        $html .= '<p>Шаблон: цена от-цена до:наценка;цена от-цена до:наценка;</p>';
        $html .= '</div></div>';
        return $html;
    }

    public function get_data($data){

        if(!@$data['id']){
            $data['id'] = 0;
        }
        $tmp_prefix = $this->CI->db->dbprefix;
        $this->CI->db->dbprefix = '';
// unusual query runs here

        $this->CI->db->select('
        ax_product.*, 
        ax_product_price.*,
        ax_category.name as category_name,
        ax_currency.name as currency_name,
        ax_currency.value as currency_value,
        ax_supplier.name as supplier_name,
        ax_product_price.price * ax_currency.value as calculate_price
        ', FALSE);

        $this->CI->db->from('ax_product_price');

        $this->CI->db->join('ax_currency','ax_currency.id=ax_product_price.currency_id','left');
        $this->CI->db->join('ax_product','ax_product.id=ax_product_price.product_id','left');
        $this->CI->db->join('ax_category','ax_category.id=ax_product.category_id','left');
        $this->CI->db->join('ax_supplier','ax_supplier.id=ax_product_price.supplier_id','left');

        $this->CI->db->where('ax_product_price.product_id >',(int)@$data['id']);

        if(@$data['category_id']){
            $this->CI->db->where('ax_category.id',(int)$data['category_id']);
        }

        if(@$data['supplier_id']){
            $this->CI->db->where('ax_supplier.id',(int)$data['supplier_id']);
        }

        if(@$data['saleprice']){
            $this->CI->db->where('ax_product_price.saleprice >',0);
        }

        if(@$data['status']){
            $this->CI->db->where('ax_product_price.status',true);
        }

        if(@$data['brand']){
            $this->CI->db->where_in('ax_product.brand',explode(',',$data['brand']));
        }

        if(@$data['exclude_brand']){
            $this->CI->db->where_not_in('ax_product.brand',explode(',',$data['exclude_brand']));
        }

        if(@$data['price_from']){
            $this->CI->db->where('ax_product_price.price >=',(float)$data['price_from']);
        }

        if(@$data['price_to']){
            $this->CI->db->where('ax_product_price.price <=',(float)$data['price_to']);
        }

        if(@$data['term_from']){
            $this->CI->db->where('ax_product_price.term >=',(float)$data['term_from']);
        }

        if(@$data['term_to']){
            $this->CI->db->where('ax_product_price.term <=',(float)$data['term_to']);
        }

        if(@$data['unique']){
            $this->CI->db->group_by('id');
        }

        $this->CI->db->order_by('id','ASC');
        if(@$data['unique']){
            $this->CI->db->order_by('calculate_price','ASC');
        }

        $this->CI->db->limit(10000);
        $query = $this->CI->db->get();

        if($query->num_rows() == 0 && $data['id'] == 0){
            exit('<a href="'.base_url('autoxadmin/price').'">Home</a><br/>Empty results');
        }
        if($query->num_rows() > 0){
            $content = '';
            $results = $query->result_array();
            foreach ($results as $result){
                $product = [
                    $result['category_name'],
                    $result['brand'],
                    $result['name'],
                    $result['sku'],
                    $result['id'],
                    $result['description'],
                    $result['price'] * $result['currency_value'],
                    'от производителя',
                    $result['quantity'] > 0 ? 'в наличии' : 'под заказ',
                    0,
                    base_url('product/'.$result['slug']),
                    $result['image'] ? base_url('uploads/product/'.$result['image']) : ''
                ];
                $content .= implode(';',$product).PHP_EOL;
            }
            if($data['id'] == 0){
                file_put_contents('./uploads/price/hotline/price.csv',$content);
            }else{
                file_put_contents('./uploads/price/hotline/price.csv',$content,FILE_APPEND);
            }
            $data['id'] = $result['id'];
            echo('<html>
                    <head>
                    <title>Export...</title>
                    </head>
                    <body>
                    Export...<br /><a id="go" href=\'/autoxadmin/price/get_data?library_name=Hotline&data='.serialize($data).'\'>.</a>
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
