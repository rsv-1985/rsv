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
            $html .= '<select name="supplier_id[]" class="form-control" multiple>';
            $html .= '<option></option>';
            foreach ($suppliers as $supplier){
                $html .= '<option value="'.$supplier['id'].'">'.$supplier['name'].'</option>';
            }
            $html .= '</select>';
            $html .= '</div>';
        }

        $this->CI->load->model('customergroup_model');
        $groups = $this->CI->customergroup_model->get_group();

        if($suppliers){
            $html .= '<div class="form-group"><label>Группа покупателей</label>';
            $html .= '<select name="customer_group_id" class="form-control">';
            foreach ($groups as $group){
                $html .= '<option value="'.$group['id'].'">'.$group['name'].'</option>';
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

        $html .= '<h4>Формат файла</h4>';
        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[sku]" value="1">Артикул</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[brand]" value="1">Производитель</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[name]" value="1">Наименование</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[slug]" value="1">Ссылка на товар</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[category_name]" value="1">Название категории</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[excerpt]" value="1">Доп. информация по товару</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[currency_name]" value="1">Валюта прайса</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[currency_value]" value="1">Курс валюты</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[delivery_price]" value="1">Цена доставки</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[saleprice]" value="1">Акционная цена</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[price]" value="1">Цена сайта</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[quantity]" value="1">Количество</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[supplier_name]" value="1">Поставщик</label>';
        $html .= '</div></div>';

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[term]" value="1">Срок поставки в часах</label>';
        $html .= '</div></div>';

        return $html;
    }

    public function get_data($data){

        $this->CI->load->model('product_model');
        $this->CI->load->model('customergroup_model');
        $this->CI->load->model('customer_group_pricing_model');

        $this->CI->customergroup_model->customer_group = $this->CI->customergroup_model->get($data['customer_group_id']);
        $this->CI->customer_group_pricing_model->pricing = $this->CI->customer_group_pricing_model->get_customer_group_pricing($data['customer_group_id']);

        if(!@$data['id']){
            $data['id'] = 0;
        }

        $sql = "SELECT p.sku,
         p.brand,
         p.name,
         p.slug,
         pp.product_id,
         pp.excerpt,
         pp.delivery_price,
         pp.saleprice,
         pp.price,
         pp.quantity,
         pp.currency_id,
         pp.supplier_id,
         pp.term 
          ";
        if(isset($data['template']['category_name'])){
            $sql .= " ,(SELECT name FROM ax_category c WHERE c.id = p.category_id ) as category_name";
        }
        if(isset($data['template']['currency_name'])){
            $sql .= " ,(SELECT name FROM ax_currency cur WHERE cur.id = pp.currency_id ) as currency_name";
        }
        if(isset($data['template']['currency_value'])){
            $sql .= " ,(SELECT value FROM ax_currency cur WHERE cur.id = pp.currency_id ) as currency_value";
        }
        if(isset($data['template']['supplier_name'])){
            $sql .= " ,(SELECT name FROM ax_supplier s WHERE s.id = pp.supplier_id ) as supplier_name";
        }

        $sql .= " FROM ax_product_price pp";
        $sql .= " LEFT JOIN ax_product p ON p.id = pp.product_id";
        $sql .= " WHERE 1";

        if($data['category_id']){
           $sql .= " AND p.category_id = '".(int)$data['category_id']."'";
        }
        if($data['supplier_id']){
            $sql .= " AND pp.supplier_id IN ('".implode('\',\'',$data['supplier_id'])."')";
        }
        if($data['saleprice']){
            $sql .= " AND pp.saleprice > 0";
        }
        if($data['brand']){
            $sql .= " AND p.brand = ".$this->CI->db->escape($data['brand']);
        }
        if($data['exclude_brand']){
            $sql .= " AND p.brand NOT IN ('".implode('\',\'',explode(',',$data['exclude_brand']))."')";
        }
        if($data['term_from']){
            $sql .= " AND pp.term >= '".(int)$data['term_from']."'";
        }
        if($data['term_to']){
            $sql .= " AND pp.term <= '".(int)$data['term_to']."'";
        }

        $sql .= " AND pp.product_id > ".$data['id'];

        $sql .= " ORDER BY pp.product_id ASC";

        $sql .= " LIMIT 500";

        $query = $this->CI->db->query($sql);

        if($query->num_rows() == 0 && $data['id'] == 0){
            exit('<a href="'.base_url('autoxadmin/price').'">Home</a><br/>Товары по фильтру не найдены');
        }
        if($query->num_rows() > 0){

            $products = $query->result_array();

            foreach ($products as $product){

                $product['slug'] = base_url('product/'.$product['slug']);
                if($data['margin']){
                    $margins = explode(';',$data['margin']);
                    if(count($margins)){
                        foreach ($margins as $margin){
                            $margin = explode(':',$margin);
                            if(count($margin)){
                                $percent = @$margin[1];
                                $margin_price = explode('-',$margin[0]);
                                $price_from = @$margin_price[0];
                                $price_to = @$margin_price[1];
                                if($percent > 0 && $price_from >= 0 && $price_to > 0 && $product['delivery_price'] >= $price_from && $product['delivery_price'] <=  $price_to){
                                    $product['price'] = round($product['delivery_price'] + $product['delivery_price'] * $percent / 100,2);
                                }
                            }
                        }
                    }
                }else{
                    $product['price'] = $this->CI->product_model->calculate_customer_price($product);
                }

                if($data['price_from'] != '' && $product['price'] < $data['price_from']){
                    continue;
                }

                if($data['price_to'] != '' && $product['price'] > $data['price_to']){
                    continue;
                }

                if($data['id'] == 0){
                    @unlink('./uploads/price/csv/price.csv');
                    $data['id'] = $product['product_id'];
                    $fp = fopen('./uploads/price/csv/price.csv', 'w');
                    $product = array_intersect_key($product,$data['template']);
                    fputcsv($fp, array_keys($product));
                }else{
                    $data['id'] = $product['product_id'];
                    $fp = fopen('./uploads/price/csv/price.csv', 'a');
                }

                $product = array_intersect_key($product,$data['template']);

                fputcsv($fp, $product);


            }

            echo('<html>
                    <head>
                    <title>Export...</title>
                    </head>
                    <body>
                    Export...<br /><a id="go" href=\'/autoxadmin/price/get_data?library_name=Csv&data='.serialize($data).'\'>.</a>
                    <script type="text/javascript">document.getElementById(\'go\').click();</script>
                    </body>
                    </html>');
            die();
        }else{
            echo 'Link to price: <a download href="'.base_url().'uploads/price/csv/price.csv?ver='.time().'">'.base_url().'uploads/price/csv/price.csv</a>';
            exit();
        }
    }

}
