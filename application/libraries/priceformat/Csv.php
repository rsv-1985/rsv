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

        $html .= '<div class="form-group"><div class="checkbox"><label>';
        $html .= '<input type="checkbox" checked name="template[status]" value="1">Статус</label>';
        $html .= '</div></div>';
        return $html;
    }

    public function get_data($data){

        if(!@$data['id']){
            $data['id'] = 0;
        }

        $this->CI->db->select('
        product.*, 
        product_price.*,
        category.name as category_name,
        currency.name as currency_name,
        currency.value as currency_value,
        supplier.name as supplier_name,
        ', false);


        $this->CI->db->from('product');
        $this->CI->db->join('product_price','product_price.product_id=product.id');
        $this->CI->db->join('category','category.id=product.category_id','left');
        $this->CI->db->join('supplier','supplier.id=product_price.supplier_id','left');
        $this->CI->db->join('currency','currency.id=product_price.currency_id','left');

        $this->CI->db->where('product.id >',(int)@$data['id']);

        if(@$data['category_id']){
            $this->CI->db->where('category.id',(int)$data['category_id']);
        }

        if(@$data['supplier_id']){
            $this->CI->db->where('supplier.id',(int)$data['supplier_id']);
        }

        if(@$data['saleprice']){
            $this->CI->db->where('product_price.saleprice >',0);
        }

        if(@$data['status']){
            $this->CI->db->where('product_price.status',true);
        }

        if(@$data['brand']){
            $this->CI->db->where_in('product.brand',explode(',',$data['brand']));
        }

        if(@$data['exclude_brand']){
            $this->CI->db->where_not_in('product.brand',explode(',',$data['exclude_brand']));
        }

        if(@$data['term_from']){
            $this->CI->db->where('product_price.term >=',(float)$data['term_from']);
        }

        if(@$data['term_to']){
            $this->CI->db->where('product_price.term <=',(float)$data['term_to']);
        }

        if(@$data['unique']){
            $this->CI->db->group_by('id');
        }


        $this->CI->db->order_by('product.id','ASC');

        $this->CI->db->limit(10000);
        $query = $this->CI->db->get();
        if($query->num_rows() == 0 && $data['id'] == 0){
            exit('<a href="'.base_url('autoxadmin/price').'">Home</a><br/>Товары по фильтру не найдены');
        }
        if($query->num_rows() > 0){

            if($data['id'] == 0){
                $fp = fopen('./uploads/price/csv/price.csv', 'w');
                fputcsv($fp, array_keys($data['template']));
            }else{
                $fp = fopen('./uploads/price/csv/price.csv', 'a');
            }


            $products = $query->result_array();
            foreach ($products as $product){
                $data['id'] = $product['id'];
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

                    $price = $product['delivery_price'] * $this->CI->currency_model->currencies[$product['currency_id']]['value'];

                    //Ценообразование по поставщику
                    if ($this->CI->pricing_model->pricing && isset($this->CI->pricing_model->pricing[$product['supplier_id']])) {
                        foreach ($this->CI->pricing_model->pricing[$product['supplier_id']] as $supplier_price) {
                            if ($supplier_price['price_from'] <= $price && $supplier_price['price_to'] >= $price) {

                                if ($supplier_price['brand'] && $product['brand'] != $supplier_price['brand']) {
                                    continue;
                                }

                                if ($supplier_price['brand'] && $product['brand'] == $supplier_price['brand']) {
                                    switch ($supplier_price['method_price']) {
                                        case '+':
                                            $price = $price + $price * $supplier_price['value'] / 100;
                                            break;
                                        case '-':
                                            $price = $price - $price * $supplier_price['value'] / 100;
                                            break;
                                    }
                                    break;
                                }

                                switch ($supplier_price['method_price']) {
                                    case '+':
                                        $price = $price + $price * $supplier_price['value'] / 100;
                                        break;
                                    case '-':
                                        $price = $price - $price * $supplier_price['value'] / 100;
                                        break;
                                }
                                break;
                            }
                        }
                    }
                    $product['price'] = $price;
                }

                if($data['price_from'] != '' && $product['price'] < $data['price_from']){
                    continue;
                }

                if($data['price_to'] != '' && $product['price'] > $data['price_to']){
                    continue;
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
            echo 'Link to price: <a href="'.base_url().'uploads/price/csv/price.csv">'.base_url().'uploads/price/csv/price.csv</a>';
            exit();
        }
    }

}
