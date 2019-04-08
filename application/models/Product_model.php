<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends Default_model
{
    public $table = 'product';
    public $total_rows = 0;
    public $seo_url_template;
    public $product;

    public function __construct()
    {
        parent::__construct();
        $this->seo_url_template = $this->settings_model->get_by_key('seo_url_template');
    }

    public function getSlug($product)
    {
        $slug = url_title($product['name'] . ' ' . $product['sku'] . ' ' . $product['brand'], 'dash', true);
        $seo_url_template = $this->seo_url_template;
        if ($seo_url_template) {
            $replace = array_map(function ($str) {
                return '{' . $str . '}';
            }, array_keys($product));
            $slug = url_title(str_replace($replace, array_values($product), $seo_url_template));
        }
        return $slug;
    }

    public function product_count_all($where = false)
    {
        if ($where) {
            foreach ($where as $field => $value) {
                $this->db->where($field, $value);
            }
            return $this->db->count_all_results('product_price');
        } else {
            return $this->db->count_all('product_price');
        }
    }

    public function product_delete($where)
    {
        foreach ($where as $field => $value) {
            $this->db->where($field, $value);
        }
        $this->db->delete('product_price');
    }

    public function admin_product_get_all($limit = false, $start = false, $return_count = false)
    {
        $this->db->from('product p');
        $this->db->select('pp.*, p.*, c.name as category_name, s.name as supplier_name, cr.name as currency_name');
        if (!$return_count || $this->input->get()) {
            $this->db->join('product_price pp', 'p.id=pp.product_id', 'left');
            $this->db->join('category c', 'c.id = p.category_id', 'left');
            $this->db->join('supplier s', 's.id=pp.supplier_id', 'left');
            $this->db->join('currency cr', 'cr.id = pp.currency_id', 'left');
        }


        if ($this->input->get()) {
            if ($this->input->get('sku')) {
                $this->db->where('p.sku', clear_sku($this->input->get('sku', true)));
            }
            if ($this->input->get('brand')) {
                $this->db->where('p.brand', $this->input->get('brand', true));
            }
            if ($this->input->get('name')) {
                $this->db->like('p.name', $this->input->get('name', true));
            }

            if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
                $this->db->where('p.category_id', (int)$this->input->get('category_id'));
            }

            if ($this->input->get('supplier_id')) {
                $this->db->where('pp.supplier_id', $this->input->get('supplier_id', true));
            }
        }


        if ($limit && $start) {
            $this->db->limit((int)$limit, (int)$start);
        } elseif ($limit) {
            $this->db->limit((int)$limit);
        }

        if ($return_count) {
            return $this->db->count_all_results();
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function update_stock($product, $method = '-')
    {
        $this->db->where('product_id', (int)$product['product_id']);
        $this->db->where('supplier_id', (int)$product['supplier_id']);
        $this->db->where('term', (int)$product['term']);
        if ($method == '+') {
            $this->db->set('quantity', 'quantity + ' . (int)$product['quantity'], FALSE);
        } else {
            $this->db->set('quantity', 'quantity - ' . (int)$product['quantity'], FALSE);
        }
        $this->db->update('product_price');
    }

    public function update_bought($product)
    {
        $this->db->where('id', (int)$product['product_id']);
        $this->db->set('bought', 'bought + 1', FALSE);
        $this->db->update($this->table);
    }

    public function update_viewed($product_id)
    {
        $this->db->where('id', (int)$product_id);
        $this->db->set('viewed', 'viewed + 1', FALSE);
        $this->db->update($this->table);
    }

    //Обновление данных товара с админки
    public function update_item($data, $product_id, $supplier_id, $term)
    {
        $this->db->where('product_id', (int)$product_id);
        $this->db->where('supplier_id', (int)$supplier_id);
        $this->db->where('term', (int)$term);
        $this->db->update('product_price', $data);
    }

    public function price_insert($prices)
    {
        $fields = [
            'product_id',
            'excerpt',
            'currency_id',
            'delivery_price',
            'saleprice',
            'quantity',
            'supplier_id',
            'term',
            'created_at',
            'updated_at',
        ];

        $first = true;
        $values = '';
        foreach ($prices as $price) {
            if ($first) {
                $values .= "(" . implode(',', $price) . ")";
            } else {
                $values .= ",(" . implode(',', $price) . ")";
            }
            $first = false;
        }


        $sql = "INSERT INTO `ax_product_price` (`" . implode('`,`', $fields) . "`) VALUES " . $values . "ON DUPLICATE KEY UPDATE
            excerpt=VALUES(excerpt),
            currency_id=VALUES(currency_id),
            delivery_price=VALUES(delivery_price),
            saleprice=VALUES(saleprice),
            quantity=VALUES(quantity),
            term=VALUES(term),
            updated_at=VALUES(updated_at)
            ;";

        $this->db->query($sql);
    }

    public function product_insert($product, $update = false, $update_seo_url = false)
    {
        $this->db->select('id');
        $this->db->where('sku', $product['sku']);
        $this->db->where('brand', $product['brand']);
        $query = $this->db->get('product');
        if ($query->num_rows() > 0) {
            $product_id = $query->row_array()['id'];
            if ($update) {
                if (!$update_seo_url) {
                    unset($product['slug']);
                }
                $this->insert($product, $product_id);
            }
        } else {
            $product_id = $this->insert($product);
        }
        return $product_id;
    }

    //При добавлении и обновлении синонима бренда
    public function update_brand($brand1, $brand2)
    {
        $this->db->db_debug = FALSE;
        $this->db->where('brand', $brand1);
        $this->db->set('brand', $brand2);
        $this->db->update($this->table);

        $this->db->where('brand', $brand1);
        $this->db->delete($this->table);
    }

    //Очистка номера от лишних сиволов
    public function clear_sku($sku)
    {
        return str_replace('_', '', mb_strtoupper(preg_replace('/[^\w]+/u', '', $sku)));
    }

    //Чистка цены от лишних сиволов
    public function clear_price($price)
    {
        return (float)preg_replace("/[^0-9,.]+/iu", "", str_replace(',', '.', $price));
    }

    //Чистка бренда
    public function clear_brand($brand, $synonym = false)
    {
        $brand = trim(mb_strtoupper($brand, 'UTF-8'));
        if ($synonym) {
            if (isset($synonym[$brand])) {
                $brand = $synonym[$brand];
            }
        }
        return $brand;
    }

    //Чистка количества
    public function clear_quan($q)
    {
        return (int)preg_replace("/[^-0-9\.]/", "", $q);
    }

    //Получаем кросс номера
    public function get_crosses($brand, $sku)
    {

        //Получаем кросс номера
        $sku = clear_sku($sku);
        if(is_array($brand)){
            $brand = array_map('clear_brand', $brand);
        }else{
            $brand = clear_brand($brand);
        }


        $crosses = [];

        $this->load->library('td');

        if($brand){
            if(is_array($brand)){
                $tdcross = $this->td->getCrossesBrandGroup($sku, $brand);
            }else{
                $tdcross = $this->td->getCrosses($sku, $brand);
            }


            if ($tdcross) {
                foreach (array_slice($tdcross, 0, 1000) as $item) {
                    if (clear_sku($item['sku']) == $sku && $item['brand'] == $brand) {
                        continue;
                    }
                    $crosses[] = [
                        'sku' => clear_sku($item['sku']),
                        'brand' => $item['brand']
                    ];
                }
            }
        }

        //Получаем собственные кроссы
        $this->db->select(['code2 as sku', 'brand2 as brand']);
        $this->db->from('cross');
        $this->db->where('code', $sku);
        if ($brand) {
            if(is_array($brand)){
                $this->db->where_in('brand', $brand);
            }else{
                $this->db->where('brand', $brand);
            }

        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $crosses = array_merge($crosses, $query->result_array());
        }

        if($brand){
            if(is_array($brand)){
                $autox_crosses = $this->autox_cross->getCrosses($sku, $brand[0]);
            }else{
                $autox_crosses = $this->autox_cross->getCrosses($sku, $brand);
            }


            if ($autox_crosses) {
                foreach ($autox_crosses as $ac) {
                    $crosses[] = ['sku' => $ac->article, 'brand' => $ac->brand];
                }
            }
        }


        return array_unique($crosses,SORT_REGULAR);
    }

    //Получаем бренды для уточнения поиска
    public function get_brands($query)
    {
        $sku = clear_sku($query);
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
                    'brand' => clear_brand(preg_replace('~^OE ~', '', $item->Brand)),
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

    //Поиск запчастей по точному совпадению
    public function get_search_products($sku, $brand)
    {
        $sku = clear_sku($sku);

        $this->db->from('product');
        $this->db->where('sku', $sku);
        $this->db->where('brand', $brand);
        $this->db->limit(500);
        $query = $this->db->get();

        $product = false;
        if ($query->num_rows() > 0) {
            $product = $query->row_array();
            $product['prices'] = $this->get_product_price($product);

        }
        return $product;
    }

    //Поиск запчастей
    public function get_search($search)
    {
        $this->db->from('product');
        foreach ($search as $item) {
            $this->db->or_group_start();
            $this->db->where('sku', clear_sku($item['sku']));
            $this->db->where('brand', clear_brand($item['brand']));
            $this->db->group_end();
        }
        $this->db->where("(SELECT count(*) FROM ax_product_price WHERE product_id = id) > 0");
        $query = $this->db->get();

        $products = false;
        if ($query->num_rows() > 0) {
            $products = $query->result_array();
            foreach ($products as &$product) {
                $product['prices'] = $this->get_product_price($product);
            }
        }
        return $products;
    }

    //Поиск запчастей по кросс номерам
    public function get_search_crosses($crosses)
    {
        $this->db->from('product');
        foreach ($crosses as $cross) {
            $this->db->or_group_start();
            $this->db->where('sku', $cross['sku']);
            $this->db->where('brand', $cross['brand']);
            $this->db->group_end();
        }
        $this->db->limit(500);
        $query = $this->db->get();

        $products = false;
        if ($query->num_rows() > 0) {
            $products = $query->result_array();
            foreach ($products as &$product) {
                $product['prices'] = $this->get_product_price($product);
            }
        }
        return $products;
    }

    //Поиск запчастей по тексу
    public function get_search_text($search)
    {
        $products = false;

        $search = explode(' ', trim($search));
        $this->db->from('product');

        foreach ($search as $search) {
            $this->db->group_start();
            $this->db->or_where('sku', clear_sku($search));
            $this->db->or_like('name', $search, 'both');
            $this->db->or_like('brand', $search, 'both');
            $this->db->group_end();
        }
        $this->db->where('(SELECT delivery_price FROM ax_product_price WHERE product_id = id LIMIT 1) > 0', NULL, FALSE);
        $this->db->limit(100);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as &$item) {
                $prices = $this->get_product_price($item);
                if ($prices) {
                    $item['prices'] = $prices;
                    $products[] = $item;
                }
            }

        }

        return $products;
    }

    public function get_product_price($product, $calculate = true)
    {
        $product_prices = [];
        $this->db->where('product_id', (int)$product['id']);
        $this->db->where('quantity >', 0);

        $query = $this->db->get('product_price');
        if ($query->num_rows() > 0) {
            $product_prices = $query->result_array();
            if ($calculate) {
                foreach ($product_prices as &$product_price) {
                    $product_price['price'] = $this->calculate_customer_price(array_merge($product, $product_price));
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

    //Расчет цены по группе покупателя
    public function calculate_customer_price($product)
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

//Новинки
    public
    function get_novelty()
    {
        $cache = $this->cache->file->get('novelty');
        if (!$cache && !is_null($cache)) {
            return false;
            $this->db->join('product', 'product.id=product_price.product_id');
            $this->db->order_by('created_at', 'DESC');
            $this->db->limit(3);
            $query = $this->db->get('product_price');
            if ($query->num_rows() > 0) {
                $results = $query->result_array();

                foreach ($results as &$result) {
                    $result['price'] = $this->calculate_customer_price($result);
                    $tecdoc_info = $this->tecdoc_info($result['sku'], $result['brand']);
                    if (!empty($result['image'])) {
                        $result['image'] = '/uploads/product/' . $result['image'];
                    } else {
                        $result['image'] = theme_url() . 'img/no_image.png';
                    }
                    $result['brand_image'] = false;
                    if ($tecdoc_info) {
                        $result['image'] = isset($tecdoc_info['article']['Image']) && strlen($tecdoc_info['article']['Image']) > 0 ? $tecdoc_info['article']['Image'] : $result['image'];
                        $result['brand_image'] = isset($tecdoc_info['article']['Logo']) && strlen($tecdoc_info['article']['Logo']) > 0 ? $tecdoc_info['article']['Logo'] : false;
                        $result['name'] = mb_strlen($result['name'] == 0) ? @$tecdoc_info['article']['Name'] : $result['name'];
                    }
                }
                $this->cache->file->save('novelty', $results, 604800);
                return $results;
            }
            $this->cache->file->save('novelty', null, 604800);
            return false;
        } else {
            return $cache;
        }

    }

//Топ
    public
    function top_sellers()
    {
        $cache = $this->cache->file->get('top_sellers');
        if (!$cache && !is_null($cache)) {
            $this->db->join('product', 'product.id=product_price.product_id');
            $this->db->order_by('bought', 'DESC');
            $this->db->limit(3);
            $query = $this->db->get('product_price');
            if ($query->num_rows() > 0) {
                $results = $query->result_array();

                foreach ($results as &$result) {
                    $result['price'] = $this->calculate_customer_price($result);
                    $tecdoc_info = $this->tecdoc_info($result['sku'], $result['brand']);
                    if (!empty($result['image'])) {
                        $result['image'] = '/uploads/product/' . $result['image'];
                    } else {
                        $result['image'] = theme_url() . 'img/no_image.png';
                    }

                    $result['brand_image'] = false;
                    if ($tecdoc_info) {
                        $result['image'] = strlen(@$tecdoc_info['article']['Image']) > 0 ? @$tecdoc_info['article']['Image'] : $result['image'];
                        $result['brand_image'] = strlen(@$tecdoc_info['article']['Logo']) > 0 ? @$tecdoc_info['article']['Logo'] : false;
                        $result['name'] = strlen($result['name'] == 0) ? @$tecdoc_info['article']['Name'] : $result['name'];
                    }
                }
                $this->cache->file->save('top_sellers', $results, 604800);
                return $results;
            }
            $this->cache->file->save('top_sellers', null, 604800);
            return false;
        } else {
            return $cache;
        }

    }

//Информация по запчасти с текдока
    function tecdoc_info($sku, $brand, $full_info = false)
    {
        $return = false;
        //Для совместимости с текдок
        if ($this->config->item('api_url') == 'https://td.autox.pro/?json=') {
            if ($sku && $brand) {
                $tecdoc_info = $this->tecdoc->getInfo($sku, $brand, $full_info);

                if ($tecdoc_info) {
                    $return['article'] = (array)$tecdoc_info->article;
                    if ($full_info) {
                        $crosses = $this->get_crosses($brand, $sku);
                        if ($crosses) {
                            $return['cross'] = $this->get_search_crosses($crosses);
                        }

                        $return['applicability'] = (array)$tecdoc_info->applicability;
                        $return['components'] = (array)$tecdoc_info->components;
                        $return['images'] = (array)$tecdoc_info->images;
                    }
                }
            }
        } else {
            if ($sku && $brand) {
                $ID_art = $this->tecdoc->getIDart($sku, $brand);
                if ($full_info) {
                    $crosses = $this->get_crosses($brand, $sku);
                    if ($crosses) {
                        $return['cross'] = $this->get_search_crosses($crosses);
                    }
                }
                if (isset($ID_art[0]->ID_art)) {
                    $ID_art = $ID_art[0]->ID_art;
                    $return['article'] = (array)$this->tecdoc->getArticle($ID_art)[0];
                    if ($full_info) {
                        $return['applicability'] = $this->tecdoc->getUses($ID_art);
                        $return['components'] = $this->tecdoc->getPackage($ID_art);
                        $return['images'] = $this->tecdoc->getImages($ID_art);
                    }
                }
            }
        }


        return $return;
    }

    public
    function get_by_slug($slug, $get_tecdoc_info = true)
    {
        $this->db->from($this->table);
        $this->db->where('slug', $slug);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row_array();

            //Получаем цены по апи
            $this->load->library('user_agent');

            if (!$this->agent->is_robot()) {
                $this->api_supplier($result['sku'], $result['brand'], false);
            }
            if ($get_tecdoc_info) {
                $result['tecdoc_info'] = $this->tecdoc_info($result['sku'], $result['brand'], true);
            }
            return $result;
        }
        return false;
    }

//Для карты сайта
    public function get_sitemap($id)
    {
        $return = false;
        $this->db->select('id, slug,(SELECT MAX(updated_at) FROM ax_product_price WHERE product_id = id) as updated_at', false);
        $this->db->from($this->table);
        $this->db->limit(5000);
        $this->db->where('id >', $id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $return = [];
            foreach ($query->result_array() as $row) {
                $return['urls'][] = [
                    'url' => base_url('product/' . $row['slug']),
                    'updated_at' => $row['updated_at']
                ];
            }
            $return['id'] = $row['id'];
        }
        return $return;
    }

    public
    function get_product_for_cart($product_id, $supplier_id, $term)
    {
        $this->db->from('product_price');
        $this->db->where('product_id', (int)$product_id);
        $this->db->where('supplier_id', (int)$supplier_id);
        $this->db->where('term', (int)$term);
        $this->db->join('product', 'product.id=product_price.product_id');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $product = $query->row_array();
            $product['price'] = $this->calculate_customer_price($product);
            return $product;
        }
        return false;
    }

//API поставщиков
    public
    function api_supplier($sku, $brand, $crosses_search)
    {
        $cross_suppliers = [];
        $api_supplier = $this->db->select(['id', 'api'])->where('api !=', '')->get('supplier')->result_array();
        if ($api_supplier) {
            foreach ($api_supplier as $supplier) {
                if (file_exists('./application/libraries/apisupplier/' . ucfirst($supplier['api']) . '.php')) {
                    echo $supplier['api'].'<br/>';
                    $this->load->library('apisupplier/' . $supplier['api']);
                    $cross_suppliers[] = $this->{$supplier['api']}->get_search($supplier['id'], $sku, $brand, $crosses_search);
                }
            }
        }
        return $cross_suppliers;
    }

    public function api_supplier_brand($sku)
    {
        $brands = [];
        $api_supplier = $this->db->select(['id', 'api'])->where('api !=', '')->get('supplier')->result_array();
        if ($api_supplier) {
            foreach ($api_supplier as $supplier) {
                if (file_exists('./application/libraries/apisupplier/' . ucfirst($supplier['api']) . '.php')) {
                    $this->load->library('apisupplier/' . $supplier['api']);
                    $brands[] = @$this->{$supplier['api']}->get_brands($sku);
                }
            }
        }
        return $brands;
    }

    public function getProductImages($product_id)
    {
        $this->db->where('product_id', (int)$product_id);
        $this->db->order_by('id', 'ASC');
        return $this->db->get('product_images')->result_array();
    }
}