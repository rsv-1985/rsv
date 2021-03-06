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
            $html .= '<select name="supplier_id[]" class="form-control" multiple>';
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
        $this->CI->load->model('product_model');
        
        if(!@$data['id']){
            $data['id'] = 0;
        }


        $this->CI->db->select('
        product.*, 
        product_price.*,
        (SELECT name FROM ax_category WHERE ax_category.id= ax_product.category_id) as category_name', false);

        $this->CI->db->from('product');
        $this->CI->db->join('product_price','product_price.product_id=product.id','left');

        $this->CI->db->where('product.id >',(int)@$data['id']);
        $this->CI->db->where('product_price.delivery_price != ', null);

        if(@$data['category_id']){
            $this->CI->db->where('category.id',(int)$data['category_id']);
        }

        if(@$data['supplier_id']){
            $this->CI->db->where_in('supplier_id',$data['supplier_id']);
        }

        if(@$data['saleprice']){
            $this->CI->db->where('product_price.saleprice >',0);
        }

        if(@$data['brand']){
            $this->CI->db->where_in('product.brand',explode(',',$data['brand']));
        }

        if(@$data['exclude_brand']){
            $this->CI->db->where_not_in('product.brand',explode(',',$data['exclude_brand']));
        }

        if(@$data['unique']){
            $this->CI->db->group_by('id');
        }

        $this->CI->db->order_by('id','ASC');

        if(@$data['unique']){
            $this->CI->db->group_by('id');
        }

        $this->CI->db->limit(1000);
        $query = $this->CI->db->get();

        if($query->num_rows() == 0 && $data['id'] == 0){
            exit('<a href="'.base_url('autoxadmin/price').'">Home</a><br/>Empty results');
        }
        if($query->num_rows() > 0){
            $content = '';
            $products = $query->result_array();
            foreach ($products as $product){
                //Ценообразование по поставщику
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

                $item = [
                    $product['category_name'],
                    $product['brand'],
                    $product['name'],
                    $product['sku'],
                    $product['id'],
                    strip_tags(trim(preg_replace("/(\s*[\r\n]+\s*|\s+)/", ' ', $product['description']))),
                    str_replace('.',',',$product['price']),
                    'от производителя',
                    $product['quantity'] > 0 ? 'в наличии' : 'под заказ',
                    0,
                    base_url('product/'.$product['slug']),
                    $product['image'] ? base_url('uploads/product/'.$product['image']) : ''
                ];
                $content .= implode(';',$item).PHP_EOL;
            }
            if($data['id'] == 0){
                file_put_contents('./uploads/price/hotline/price.csv',$content);
            }else{
                file_put_contents('./uploads/price/hotline/price.csv',$content,FILE_APPEND);
            }
            $data['id'] = $product['id'];
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
