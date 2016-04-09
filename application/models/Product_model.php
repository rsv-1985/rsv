<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends Default_model{
    public $table = 'product';
    
    public function update_bought($slug){
        $this->db->where('slug', $slug);
        $this->db->set('bought', 'bought + 1', FALSE);
        $this->db->update($this->table);
    }
    
    public function update_viewed($slug){
        $this->db->where('slug', $slug);
        $this->db->set('viewed', 'viewed + 1', FALSE);
        $this->db->update($this->table);
    }

    public function insert_on_duplicate_key($data)
    {
        foreach($data as $data){
            unset($data['id']);
            $sql = $this->db->set($data)->get_compiled_insert($this->table) . '
            ON DUPLICATE KEY UPDATE
            name=VALUES(name),
            description=VALUES(description),
            excerpt=VALUES(excerpt),
            currency_id=VALUES(currency_id),
            delivery_price=VALUES(delivery_price),
            saleprice=VALUES(saleprice),
            quantity=VALUES(quantity),
            updated_at=VALUES(updated_at),
            category_id=VALUES(category_id),
            status=1;';
            $this->db->query($sql);
        }
        
        $this->db->cache_delete_all();
    }

    //При добавлении и обновлении синонима бренда
    public function update_brand($brand1,$brand2){
        $this->db->where('brand', $brand1);
        $this->db->set('brand', $brand2);
        $this->db->update($this->table);
        $this->db->cache_delete_all();
    }

    //Установка цен по поставщику
    public function set_price($supplier_id = false)
    {
        if($supplier_id){
            $this->query_price($supplier_id);
        }else{
            $this->load->model('supplier_model');
            $suppliers = $this->supplier_model->get_all();
            if($suppliers){
                foreach($suppliers as $supplier){
                    $this->query_price($supplier['id']);
                }
            }
        }
        $this->db->cache_delete_all();
    }

    private function query_price($supplier_id){
        $this->load->model('pricing_model');
        $pricing_array = $this->db->where('supplier_id', (int)$supplier_id)->get('pricing')->result_array();
        if (!empty($pricing_array)) {
            foreach ($pricing_array as $price) {
                if ($price['method_price'] == '+') {
                    $sql = "UPDATE `ax_product`
                                SET `price` = (`delivery_price` + (`delivery_price` * " . $price['value'] . " / 100))
                                WHERE `delivery_price` BETWEEN " . (float)$price['price_from'] . " AND " . (float)$price['price_to'] . " AND `supplier_id` = '" . (int)$supplier_id . "'";
                    $this->db->query($sql);
                } else {
                    $sql = "UPDATE `ax_product`
                                SET `price` = (`delivery_price` - (`delivery_price` * " . $price['value'] . " / 100))
                                WHERE `delivery_price` BETWEEN " . (float)$price['price_from'] . " AND " . (float)$price['price_to'] . " AND `supplier_id` = '" . (int)$supplier_id . "'";
                    $this->db->query($sql);
                }
            }
        } else {
            $sql = "UPDATE `ax_product`
                        SET `price` = `delivery_price`
                        WHERE `supplier_id` = '" . $supplier_id . "'";
            $this->db->query($sql);
        }
    }

    //Очистка номера от лишних сиволов
    public function clear_sku($sku){
        return str_replace('_','',mb_strtoupper(preg_replace('/[^\w]+/u','',$sku)));
    }

    //Чистка цены от лишних сиволов
    public function clear_price($s){
        $s = str_replace(',', '.', $s);
        $s = preg_replace("/[^0-9\.]/", "", $s);
        $s = str_replace('.', '',substr($s, 0, -3)) . substr($s, -3);
        return (float) $s;
    }

    //Чистка бренда
    public function clear_brand($brand, $synonym = false){
        if($synonym){
            if(isset($synonym[$brand])){
                $brand = trim($synonym[$brand]);
            }
        }else{
            $brand = trim(mb_strtoupper($brand,'UTF-8'));
        }
        return $brand;
    }

    //Чистка количества
    public function clear_quan($q){
        return (int)preg_replace("/[^-0-9\.]/", "", $q);
    }

    //Предпоиск для уточнения бренда поска
    public function get_pre_search($search){
        $return = [];
        $search_query = $this->clear_sku($search);
        $tecdoc_brand = false;
        //Получает бренды текдок
        $tecdoc = $this->tecdoc->getSearch($search_query);
        if($tecdoc){
            $return = [];
            $tecdoc_brand = [];
            foreach($tecdoc as $item){
                $tecdoc_brand[] = $item->Brand;
                $return[] = [
                    'ID_art' => $item->ID_art,
                    'name' => $item->Name,
                    'brand' => $item->Brand,
                    'sku' => $this->clear_sku($item->Article),
                ];
            }
        }
        //Получаем список брендов в локальной базе, которых нет в базе текдок
        $local_brand = $this->get_local_brand($search, $tecdoc_brand);
        if($local_brand){
            $return = array_merge($return, $local_brand);
        }
        return $return;
    }
    //Получаем список брендов в локальной базе, которых нет в базе текдок
    public function get_local_brand($search, $tecdoc_brand){
        $this->db->from($this->table);
        $this->db->select(['0 as ID_art','name', 'brand', 'sku']);
        $this->db->where('sku', $search);
        if($tecdoc_brand){
            $this->db->where_not_in('brand', $tecdoc_brand);
        }
        $this->db->where('status', true);
        $this->db->group_by('brand');
        
        if($this->config->item('cache_on')){
            $this->db->cache_on();
        }
        
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    //Поиск автозапчастей
    public function get_search($ID_art, $brand, $sku, $with_cross = false, $text_search = false){
        $return = [];
        $return['products'] = false;
        $return['cross'] = false;
        $return['about'] = false;
        //Массив slug чтобы не дублировать товары
        $slugs = [];

        //Проверяем залогинен ли пользователь
        $customer_group = false;
        if($this->is_login){
            $this->load->model('customergroup_model');
            $customer_group = $this->customergroup_model->get($this->session->customer_group_id);
        }
        if($with_cross){
            $search_data = [];
            //Получаем кросс номера
            if($ID_art){
                $cross = $this->tecdoc->getCrosses($ID_art);
                if($cross){
                    foreach($cross as $item){
                        if($this->clear_sku($item->Display) == $sku && $item->Brand == $brand ){
                            continue;
                        }
                        $search_data[] = [
                            'sku' => $this->clear_sku($item->Display),
                            'brand' => $item->Brand,
                            'name' => $item->Name
                        ];
                    }
                }
            }
        }



        //Здесь будем получать товары через API поставщиков
        /*$this->api_supplier()*/

        //Ищем по точному совпадению
        $this->db->from($this->table);
        $this->db->select('product.*,
        supplier.name as sup_name,
        supplier.description as sup_description,
        currency.name as cur_name,
        currency.value as cur_value,
        currency.symbol_right as cur_symbol_right,
        currency.symbol_left as cur_symbol_left,
        currency.decimal_place as cur_decimal_place');
        $this->db->join('supplier', 'supplier.id='.$this->table.'.supplier_id');
        $this->db->join('currency', 'currency.id='.$this->table.'.currency_id');
        $this->db->where('sku', $sku);
        $this->db->where('brand', $brand);
        $this->db->where('status', true);
        $this->db->order_by('price', 'ASC');
        $this->db->order_by('term', 'ASC');

        if($this->config->item('cache_on')){
            $this->db->cache_on();
        }

        $query = $this->db->get();
        if($query->num_rows() > 0){
            $return['products'] = $query->result_array();
            foreach($return['products'] as &$pem){
                $slugs[] = $pem['slug'];
                $pem['price'] = $this->calculate_customer_price($customer_group, $pem['price']) * $this->currency_rates[$pem['currency_id']]['value'];
            }
        }
        //Если массив кросс намиров есть и он не пустой
        if(isset($search_data) && !empty($search_data)){
            $product_cross = [];

            $this->db->from($this->table);
            $this->db->select('product.*,
            supplier.name as sup_name,
            supplier.description as sup_description,
            currency.name as cur_name,
            currency.value as cur_value,
            currency.symbol_right as cur_symbol_right,
            currency.symbol_left as cur_symbol_left,
            currency.decimal_place as cur_decimal_place');
            $this->db->join('supplier', 'supplier.id='.$this->table.'.supplier_id');
            $this->db->join('currency', 'currency.id='.$this->table.'.currency_id');

            foreach($search_data as $search_data) {
                $this->db->or_group_start();
                $this->db->where('sku', $search_data['sku']);
                $this->db->where('brand', $search_data['brand']);
                $this->db->where('status', true);
                if (count($slugs)) {
                    $this->db->where_not_in('slug', $slugs);
                }
                $this->db->group_end();
            }

            $this->db->order_by('price', 'ASC');
            $this->db->order_by('term', 'ASC');

            if($this->config->item('cache_on')){
                $this->db->cache_on();
            }

            $query = $this->db->get();
            if($query->num_rows() > 0){
                $p_cross = $query->result_array();
                foreach($p_cross as $pc){
                    $pc['price'] = $this->calculate_customer_price($customer_group, $pc['price']) * $this->currency_rates[$pc['currency_id']]['value'];
                    $product_cross[] = $pc;
                    $slugs[] = $pc['slug'];
                }
            }
        }

        if(!empty($product_cross)){
            $return['cross'] = $product_cross;
            unset($product_cross);
        }
        //Если включен режим поиска по тексту
        if($text_search){
            //Похожие товары
            $where = "";
            $query = explode(' ', trim($sku));
            $q = 0;
            foreach($query as $term){
                if($q==0){
                    $where .= "CONCAT(ax_product.sku,ax_product.brand,ax_product.name,ax_product.excerpt) LIKE '%".$this->db->escape_like_str($term)."%'";
                }else{
                    $where .= " AND CONCAT(ax_product.sku,ax_product.brand,ax_product.name,ax_product.excerpt) LIKE '%".$this->db->escape_like_str($term)."%'";
                }
                $q++;
            }
            $this->db->from($this->table);
            $this->db->select('product.*,
            supplier.name as sup_name,
            supplier.description as sup_description,
            currency.name as cur_name,
            currency.value as cur_value,
            currency.symbol_right as cur_symbol_right,
            currency.symbol_left as cur_symbol_left,
            currency.decimal_place as cur_decimal_place');
            $this->db->join('supplier', 'supplier.id='.$this->table.'.supplier_id');
            $this->db->join('currency', 'currency.id='.$this->table.'.currency_id');
            $this->db->limit(50);
            $this->db->where($where." AND status = 1");
            if(count($slugs)){
                $this->db->where_not_in('slug', $slugs);
            }
            $this->db->order_by('price', 'ASC');
            $this->db->order_by('term', 'ASC');

            if($this->config->item('cache_on')){
                $this->db->cache_on();
            }
            
            $query = $this->db->get();
            if($query->num_rows() > 0){
                $p_about = $query->result_array();
                foreach($p_about  as &$pa){
                    $pa['price'] = $this->calculate_customer_price($customer_group, $pa['price']) * $this->currency_rates[$pa['currency_id']]['value'];
                }
                $return['about'] = $p_about;
            }
        }

        return $return;
    }

    //Получаем товары для категории
    public function product_get_all($limit = false, $start = false, $where = false){
        $this->db->select('*');
        $this->db->from($this->table);
        if($where){
            foreach($where as $field => $value){
                $this->db->where($field, $value);
            }
        }
        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            //Проверяем залогинен ли пользователь
            $customer_group = false;
            if($this->is_login){
                $this->load->model('customergroup_model');
                $customer_group = $this->customergroup_model->get($this->session->customer_group_id);
            }

            $products = $query->result_array();
            foreach($products  as &$product){
                $product['price'] = $this->calculate_customer_price($customer_group, $product['price']) * $this->currency_rates[$product['currency_id']]['value'];
                $product['tecdoc_info'] = $this->tecdoc_info($product['sku'], $product['brand']);
            }
           return $products;
        }
        return false;
    }
    //Расчет цены по группе покупателя
    private function calculate_customer_price($customer_group, $price){
        if($customer_group){
            switch($customer_group['type']){
                case '+':
                    $price =$price + ($price * $customer_group['value'] / 100) + $customer_group['fix_value'];
                    break;
                case '-':
                    $price = $price - ($price * $customer_group['value'] / 100) + $customer_group['fix_value'];
                    break;
            }
        }

        return $price;
    }
    //Новинки
    public function get_novelty(){
        $this->db->where('status', true);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(3);
        if($this->config->item('cache_on')){
            $this->db->cache_on();
        }
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $results = $query->result_array();

            $customer_group = false;
            if($this->is_login){
                $this->load->model('customergroup_model');
                $customer_group = $this->customergroup_model->get($this->session->customer_group_id);
            }

            foreach($results as &$result){
                $result['price'] = $this->calculate_customer_price($customer_group, $result['price']) * $this->currency_rates[$result['currency_id']]['value'];
                $tecdoc_info = $this->tecdoc_info($result['sku'], $result['brand']);
                $result['image'] = theme_url().'img/no_image.png';
                $result['brand_image'] = false;
                if($tecdoc_info){
                    $result['image'] = strlen($tecdoc_info['article']['Image']) > 0 ? $tecdoc_info['article']['Image'] : theme_url().'img/no_image.png';
                    $result['brand_image'] =  strlen($tecdoc_info['article']['Logo']) > 0 ? $tecdoc_info['article']['Logo'] : false;
                    $result['name'] = mb_strlen($result['name'] == 0) ? $tecdoc_info['article']['Name'] : $result['name'];
                }
            }
            return $results;
        }
        return false;
    }
    //Топ
    public function top_sellers(){
        $this->db->where('status', true);
        $this->db->order_by('bought', 'DESC');
        $this->db->limit(3);
        if($this->config->item('cache_on')){
            $this->db->cache_on();
        }
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $results = $query->result_array();

            $customer_group = false;
            if($this->is_login){
                $this->load->model('customergroup_model');
                $customer_group = $this->customergroup_model->get($this->session->customer_group_id);
            }

            foreach($results as &$result){
                $result['price'] = $this->calculate_customer_price($customer_group, $result['price']) * $this->currency_rates[$result['currency_id']]['value'];
                $tecdoc_info = $this->tecdoc_info($result['sku'], $result['brand']);
                $result['image'] = theme_url().'img/no_image.png';
                $result['brand_image'] = false;
                if($tecdoc_info){
                    $result['image'] = strlen($tecdoc_info['article']['Image']) > 0 ? $tecdoc_info['article']['Image'] : theme_url().'img/no_image.png';
                    $result['brand_image'] =  strlen($tecdoc_info['article']['Logo']) > 0 ? $tecdoc_info['article']['Logo'] : false;
                    $result['name'] = strlen($result['name'] == 0) ? $tecdoc_info['article']['Name'] : $result['name'];
                }
            }
            return $results;
        }
        return false;
    }
    //Информация по запчасти с текдока
    private function tecdoc_info($sku, $brand, $full_info = false){
        $return = false;
        if($sku && $brand){
            $ID_art = $this->tecdoc->getIDart($sku, $brand);
            if(isset($ID_art[0]->ID_art)){
                $return = [];
                $ID_art = $ID_art[0]->ID_art;
                $return['article'] = (array)$this->tecdoc->getArticle($ID_art)[0];
                if($full_info){
                    $return['applicability'] = $this->tecdoc->getUses($ID_art);
                    $return['components'] = $this->tecdoc->getPackage($ID_art);
                }
            }
        }
        return $return;
    }

    public function get_by_slug($slug, $get_tecdoc_info = true){
        $this->db->from($this->table);
        $this->db->select('product.*,
        supplier.name as sup_name,
        supplier.description as sup_description,
        currency.name as cur_name,
        currency.value as cur_value,
        currency.symbol_right as cur_symbol_right,
        currency.symbol_left as cur_symbol_left,
        currency.decimal_place as cur_decimal_place');
        $this->db->join('supplier', 'supplier.id='.$this->table.'.supplier_id', 'left');
        $this->db->join('currency', 'currency.id='.$this->table.'.currency_id', 'left');
        $this->db->where('slug', $slug);
        $this->db->where('status', true);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $result = $query->row_array();
            $customer_group = false;
            if($this->is_login){
                $this->load->model('customergroup_model');
                $customer_group = $this->customergroup_model->get($this->session->customer_group_id);
            }
            $result['price'] = $this->calculate_customer_price($customer_group, $result['price']) * $this->currency_rates[$result['currency_id']]['value'];

            if($get_tecdoc_info){
                $result['tecdoc_info'] = $this->tecdoc_info($result['sku'], $result['brand'], true);
            }
            return $result;
        }
        return false;
    }

    public function admin_get_by_slug($slug, $get_tecdoc_info = true){
        $this->db->from($this->table);
        $this->db->select('product.*,
        supplier.name as sup_name,
        supplier.description as sup_description,
        currency.name as cur_name,
        currency.value as cur_value,
        currency.symbol_right as cur_symbol_right,
        currency.symbol_left as cur_symbol_left,
        currency.decimal_place as cur_decimal_place');
        $this->db->join('supplier', 'supplier.id='.$this->table.'.supplier_id', 'left');
        $this->db->join('currency', 'currency.id='.$this->table.'.currency_id', 'left');
        $this->db->where('slug', $slug);
        $this->db->where('status', true);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }
    //Для карты сайта
    public function get_sitemap($limit, $offset){
        $return = false;
        $this->db->select('slug');
        $this->db->from($this->table);
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $return = [];
            foreach($query->result_array() as $row){
                $return[] = base_url('product/'.$row['slug']);
            }
        }
        return $return;
    }
}