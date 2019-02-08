<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mproduct extends Default_model{

    CONST image_path = '/uploads/product/';

    public static $tecdocInfo;
    public $tecdocInfoArray;

    public $table = 'product';

    public function getById($id)
    {
        $this->db->where('id', (int)$id);
       
        $query = $this->db->get($this->table);

        $row = $query->custom_row_object(0, 'mproduct');

        $this->db->where('id', (int)$row->id);
        $this->db->set('viewed', 'viewed + 1', FALSE);
        $this->db->update($this->table);

        if($row){
            self::$tecdocInfo = $this->tecdoc->getInfo($row->sku, $row->brand, true);
        }

        return $row;
    }

    public function getBySlug($slug){

        $this->load->helper('security');
        $this->db->where('slug', xss_clean($slug));
       
        $query = $this->db->get($this->table);

        $row = $query->row(0, 'mproduct');

        $this->db->where('id', (int)$row->id);
        $this->db->set('viewed', 'viewed + 1', FALSE);
        $this->db->update($this->table);

        if($row){
            self::$tecdocInfo = $this->tecdoc->getInfo($row->sku, $row->brand, true);
        }

        return $row;
    }

    public function getBySkuBrand($sku, $brand){
        $this->db->where('sku', clear_brand($sku));
        $this->db->where('brand', clear_sku($brand));
       
        $query = $this->db->get($this->table);

        $row = $query->row(0, 'mproduct');

        if($row){
            self::$tecdocInfo = $this->tecdoc->getInfo($row->sku, $row->brand, true);
        }

        return $row;
    }

    public function getBrand(){
        return $this->brand;
    }

    public function getName(){
        if(@$this->options['use_tecdoc_name'] && self::$tecdocInfo->article->Name){
            return self::$tecdocInfo->article->Name;
        }

        return $this->name;
    }

    public function getCrosses(){
        $crosses = [];
        if (self::$tecdocInfo->ID_art) {
            $cross = $this->tecdoc->getCrosses(self::$tecdocInfo->ID_art);
            if ($cross) {
                foreach (array_slice($cross, 0, 1000) as $item) {
                    if (clear_sku($item->Display) == $this->sku && $item->Brand == $this->brand) {
                        continue;
                    }
                    $crosses[] = [
                        'sku' => clear_sku($item->Display),
                        'brand' => $item->Brand,
                    ];
                }
            }
        }

        //Получаем собственные кроссы
        $this->db->select(['code2 as sku', 'brand2 as brand']);
        $this->db->from('cross');
        $this->db->where('code', $this->sku);
        if ($this->brand) {
            $this->db->where('brand', $this->brand);
        }

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $crosses = array_merge($crosses, $query->result_array());
        }

        $autox_crosses = $this->autox_cross->getCrosses($this->sku, $this->brand);

        if ($autox_crosses) {
            foreach ($autox_crosses as $ac) {
                $crosses[] = ['sku' => $ac->article, 'brand' => $ac->brand];
            }
        }

        $this->load->library('user_agent');
        if ($this->agent->is_browser()){
            $cross_suppliers = $this->apiSupplier($crosses);

            if ($cross_suppliers) {
                foreach ($cross_suppliers as $cross_supplier) {
                    if($cross_supplier){
                        foreach ($cross_supplier as $cs){
                            $crosses[] = $cs;
                        }
                    }
                }
            }
        }


        if($crosses){
            $crosses = array_unique($crosses, SORT_REGULAR);
            $this->db->from('product');
            foreach ($crosses as $cross) {
                $this->db->or_group_start();
                $this->db->where('sku', $cross['sku']);
                $this->db->where('brand', $cross['brand']);
                $this->db->group_end();
            }
            $this->db->limit(500);
            $query = $this->db->get();

            return $query->custom_result_object('mproduct');
        }
    }

    public function getImages(){
        $images = [];
        if($this->image){
            $images[] = self::image_path.$this->image;
        }

        $this->db->where('product_id',(int)$this->id);
        $results = $this->db->get('product_images')->result_array();

        if($results){
            foreach ($results as $img){
                $images[] = self::image_path.$img['image'];
            }
        }

        if(isset(self::$tecdocInfo->images) && self::$tecdocInfo->images){
            foreach (self::$tecdocInfo->images as $img){
                $images[] = $img->Image;
            }
        }

        //Если нет картинок пробуем достать с td2018
        if(!$images){
            //td2018
            $this->load->library('td');

            $tdimages = $this->td->getImages($this->sku, $this->getBrand());
            if($tdimages){
                foreach ($tdimages as $img){
                    $images[] = $img;
                }
            }
        }


        return $images;
    }

    public function getPrices($calculate = false){
        $product_prices = [];
        $this->db->from('product_price pp');
        $this->db->select('pp.*, s.name as supplier, c.name as currency');
        $this->db->join('supplier s', 's.id=pp.supplier_id', 'INNER');
        $this->db->join('currency c', 'c.id=pp.currency_id', 'INNER');
        $this->db->where('pp.product_id', (int)$this->id);
        $this->db->where('pp.quantity >', 0);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $product_prices = $query->result_array();
            if ($calculate) {
                foreach ($product_prices as &$product_price) {
                    $product_price['price'] = $this->calculate_customer_price(array_merge((array)$this,$product_price));
                }
            }
        }

        if ($calculate) {
            $sort = 'price';

            if ($this->input->get('sort')) {
                switch ($this->input->get('sort')) {
                    case 'price':
                        usort($product_prices, function ($a, $b) {
                            if ($a['price'] == $b['price']) {
                                return 0;
                            }
                            return ($a['price'] < $b['price']) ? -1 : 1;
                        });
                        break;
                    case 'term':
                        usort($product_prices, function ($a, $b) {
                            if ($a['term'] == $b['term']) {
                                return 0;
                            }
                            return ($a['term'] < $b['term']) ? -1 : 1;
                        });
                        break;
                    case 'qty':
                        usort($product_prices, function ($a, $b) {
                            if ($a['quantity'] == $b['quantity']) {
                                return 0;
                            }
                            return ($a['quantity'] > $b['quantity']) ? -1 : 1;
                        });
                        break;
                    default:
                        usort($product_prices, function ($a, $b) {
                            if ($a['price'] == $b['price']) {
                                return 0;
                            }
                            return ($a['price'] < $b['price']) ? -1 : 1;
                        });
                        break;
                }
            } else {
                $like_term = [];
                $other = [];
                foreach ($product_prices as $price) {
                    if ($price['term'] < 24) {
                        $like_term[] = $price;
                    } else {
                        $other[] = $price;
                    }
                }

                if ($like_term) {
                    usort($like_term, function ($a, $b) {
                        if ($a['price'] == $b['price']) {
                            return 0;
                        }
                        return ($a['price'] < $b['price']) ? -1 : 1;
                    });
                }

                if ($other) {
                    usort($other, function ($a, $b) {
                        if ($a['price'] == $b['price']) {
                            return 0;
                        }
                        return ($a['price'] < $b['price']) ? -1 : 1;
                    });
                }

                $product_prices = array_merge($like_term, $other);
            }
        }

        return $product_prices;
    }

    private function calculate_customer_price($product)
    {
        //Статическая цена
        if ($product['static_price'] > 0) {
            $price = $product['static_price'] * $this->currency_model->currencies[$product['static_currency_id']]['value'];
        } elseif ($product['price'] > 0) {
            $price = $product['price'] * $this->currency_model->currencies[$product['currency_id']]['value'];
        } else {
            //Расчет по курсу
            $price = $product['delivery_price'] * $this->currency_model->currencies[$product['currency_id']]['value'];

            //Ценообразование по поставщику
            if ($this->pricing_model->pricing && isset($this->pricing_model->pricing[$product['supplier_id']])) {
                foreach ($this->pricing_model->pricing[$product['supplier_id']] as $supplier_price) {
                    if ($supplier_price['price_from'] <= $price && $supplier_price['price_to'] >= $price) {
                        if ($supplier_price['brand'] && $supplier_price['customer_group_id']) {
                            if ($product['brand'] != $supplier_price['brand'] || $this->customergroup_model->customer_group['id'] != $supplier_price['customer_group_id']) {
                                continue;
                            }
                            switch ($supplier_price['method_price']) {
                                case '+':
                                    if ($supplier_price['value'] > 0) {
                                        $price = $price + $price * $supplier_price['value'] / 100;
                                    }
                                    $price = $price + $supplier_price['fix_value'];
                                    break;
                                case '-':
                                    if ($supplier_price['value'] > 0) {
                                        $price = $price - $price * $supplier_price['value'] / 100;
                                    }
                                    $price = $price - $supplier_price['fix_value'];
                                    break;
                            }
                            break;
                        }

                        if ($supplier_price['brand']) {
                            if ($product['brand'] != $supplier_price['brand']) {
                                continue;
                            }
                            switch ($supplier_price['method_price']) {
                                case '+':
                                    if ($supplier_price['value'] > 0) {
                                        $price = $price + $price * $supplier_price['value'] / 100;
                                    }
                                    $price = $price + $supplier_price['fix_value'];
                                    break;
                                case '-':
                                    if ($supplier_price['value'] > 0) {
                                        $price = $price - $price * $supplier_price['value'] / 100;
                                    }
                                    $price = $price - $supplier_price['fix_value'];
                                    break;
                            }
                            break;
                        }

                        if ($supplier_price['customer_group_id']) {
                            if ($this->customergroup_model->customer_group['id'] != $supplier_price['customer_group_id']) {
                                continue;
                            }
                            switch ($supplier_price['method_price']) {
                                case '+':
                                    if ($supplier_price['value'] > 0) {
                                        $price = $price + $price * $supplier_price['value'] / 100;
                                    }
                                    $price = $price + $supplier_price['fix_value'];
                                    break;
                                case '-':
                                    if ($supplier_price['value'] > 0) {
                                        $price = $price - $price * $supplier_price['value'] / 100;
                                    }
                                    $price = $price - $supplier_price['fix_value'];
                                    break;
                            }
                            break;
                        }

                        switch ($supplier_price['method_price']) {
                            case '+':
                                if ($supplier_price['value'] > 0) {
                                    $price = $price + $price * $supplier_price['value'] / 100;
                                }
                                $price = $price + $supplier_price['fix_value'];
                                break;
                            case '-':
                                if ($supplier_price['value'] > 0) {
                                    $price = $price - $price * $supplier_price['value'] / 100;
                                }
                                $price = $price - $supplier_price['fix_value'];
                                break;
                        }
                        break;
                    }
                }
            }
        }

        //Ценообразование по группе покупателей
        $customer_price = 0;
        if (@$this->customer_group_pricing_model->pricing) {
            foreach ($this->customer_group_pricing_model->pricing as $customer_group_price) {
                if ($customer_group_price['price_from'] <= $price && $customer_group_price['price_to'] >= $price) {

                    if ($customer_group_price['brand'] && $product['brand'] != $customer_group_price['brand']) {
                        continue;
                    }

                    if ($customer_group_price['brand'] && $product['brand'] == $customer_group_price['brand']) {
                        switch ($customer_group_price['method_price']) {
                            case '+':
                                $price = $price + $price * $customer_group_price['value'] / 100;
                                break;
                            case '-':
                                $price = $price - $price * $customer_group_price['value'] / 100;
                                break;
                        }
                        $price = $price + $customer_group_price['fix_value'];
                        break;
                    }

                    switch ($customer_group_price['method_price']) {
                        case '+':
                            $price = $price + $price * $customer_group_price['value'] / 100;
                            break;
                        case '-':
                            $price = $price - $price * $customer_group_price['value'] / 100;
                            break;
                    }
                    $price = $price + $customer_group_price['fix_value'];
                    break;
                }
            }
        } else
            if ($this->customergroup_model->customer_group) {
                switch ($this->customergroup_model->customer_group['type']) {
                    case '+':
                        $customer_price = $price + ($price * $this->customergroup_model->customer_group['value'] / 100) + $this->customergroup_model->customer_group['fix_value'];
                        break;
                    case '-':
                        $customer_price = $price - ($price * $this->customergroup_model->customer_group['value'] / 100) - $this->customergroup_model->customer_group['fix_value'];
                        break;
                }
            }
        return $customer_price > 0 ? $customer_price : $price;
    }

    public function getApplicability(){
        if(isset(self::$tecdocInfo->applicability) && !empty(self::$tecdocInfo->applicability)){
            return self::$tecdocInfo->applicability;
        }

        return false;
    }

    public function getComponents(){
        if(isset(self::$tecdocInfo->components)){
            $components = [];

            foreach (self::$tecdocInfo->components as $component){
                $components[] = (array)$component;
            }

            return $components;
        }

        return false;
    }

    public function getCountReviews(){
        $this->load->model('review_model');
        return $this->review_model->count_all(['product_id' => $this->id, 'status' => true]);
    }

    public function getReviews(){
        $this->db->where('product_id',(int)$this->id);
        $this->db->where('status',1);
        $this->db->limit(10);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('review')->result_array();
    }

    public function getRating(){
        $this->db->where('product_id',(int)$this->id);
        $this->db->select_avg('rating');
        return $this->db->get('review')->row_array()['rating'];
    }

    public function getInfo(){
        if(isset(self::$tecdocInfo->article->Info) && !empty(self::$tecdocInfo->article->Info)){
            return self::$tecdocInfo->article->Info;
        }

        return false;
    }

    public function apiSupplier($crosses_search = false)
    {
        $cross_suppliers = [];
        $api_supplier = $this->db->select(['id', 'api'])->where('api !=', '')->get('supplier')->result_array();
        if ($api_supplier) {
            foreach ($api_supplier as $supplier) {
                if (file_exists('./application/libraries/apisupplier/' . ucfirst($supplier['api']) . '.php')) {
                    $this->load->library('apisupplier/' . $supplier['api']);
                    $cross_suppliers[] = $this->{$supplier['api']}->get_search($supplier['id'], $this->sku, $this->getBrand(), $crosses_search);
                }
            }
        }

        return $cross_suppliers;
    }

    public function getBrands($search)
    {
        $sku = clear_sku($search);
        $return = [];
        $check_brand = [];
        //Получает бренды текдок
        $tecdoc = $this->tecdoc->getSearch($sku);
        if ($tecdoc) {
            foreach ($tecdoc as $item) {
                //Проверяем есть бренд в группах брендов
                $check_brand[] = clear_brand(preg_replace('~^OE ~', '', $item->Brand));
                $return[] = [
                    'ID_art' => $item->ID_art,
                    'name' => $item->Name,
                    'brand' =>clear_brand(preg_replace('~^OE ~', '', $item->Brand)),
                    'sku' => clear_sku($item->Article),
                    'image' => '/image?img=' . $item->Image . '&width=50&height=50',
                ];
            }
        }

        //Получаем список брендов в локальной базе, которых нет в базе текдок
        $this->db->from('product p');
        $this->db->select("p.name, p.brand, p.sku, p.image", FALSE);
        $this->db->where('p.sku', $sku);
        if ($check_brand) {
            $this->db->where_not_in('p.brand', $check_brand);
        }
        $this->db->group_by('p.brand');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $item) {
                $check_brand[] = $item['brand'];
                $return[] = [
                    'ID_art' => 0,
                    'name' => $item['name'],
                    'brand' => $item['brand'],
                    'sku' => $item['sku'],
                    'image' => '/image?img=/uploads/product/' . $item['image'] . '&width=50&height=50',
                ];
            }
        }


        //Получаем бренды с таблицы кросов
        $this->db->from('cross c');
        $this->db->select("c.brand, c.code", FALSE);
        $this->db->where('c.code', $sku);
        if ($check_brand) {
            $this->db->where_not_in('c.brand', $check_brand);
        }
        $this->db->group_by('c.brand');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $item) {
                $check_brand[] = $item['brand'];
                $return[] = [
                    'ID_art' => 0,
                    'name' => '',
                    'brand' => $item['brand'],
                    'sku' => $item['code'],
                    'image' => '/image?width=50',
                ];
            }
        }

        unset($check_brand);
        return $return;

    }
}