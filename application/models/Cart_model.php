<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Cart_model extends CI_Model{
    public $table = 'cart';

    public function cart_insert($cart_data, $customer_id){
        $this->db->set('customer_id',(int)$customer_id);
        $this->db->set('cart_data',$cart_data);
        if($this->cart_get($customer_id)){
            $this->db->where('customer_id',(int)$customer_id);
            $this->db->update($this->table);
        }else{
            $this->db->insert($this->table);
        }

    }

    public function cart_clear($customer_id){
        $this->db->where('customer_id',(int)$customer_id)->delete($this->table);
    }

    public function cart_get($customer_id){
        $this->db->where('customer_id',(int)$customer_id);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }

        return false;
    }

    public function get_all($limit = false, $start = false, $where = false, $order = false){
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

        if($order){
            foreach($order as $field => $value){
                $this->db->order_by($field, $value);
            }
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function count_all($where = false){
        if($where){
            foreach($where as $field => $value){
                $this->db->where($field, $value);
            }
            return $this->db->count_all_results($this->table);
        }else{
            return $this->db->count_all($this->table);
        }
    }

    public function delete($customer_id){
        $this->db->where('customer_id',(int)$customer_id);
        $this->db->delete('cart');
    }

    public function addComment($comment, $customer_id){
        $this->db->set('comment',$comment);
        $this->db->where('customer_id',(int)$customer_id);
        $this->db->update($this->table);
    }

    public function addCart($product_id,$supplier_id,$term, $quantity){
        $this->load->model('product_model');
        $json = [];
        $product = $this->product_model->get_product_for_cart($product_id,$supplier_id,$term);
        if ($product) {

            $cartId = $product_id.$supplier_id.$term;
            $price = (float)$product['saleprice'] > 0 ? $product['saleprice'] : $product['price'];
            $data = [
                'id' => $cartId,
                'qty' => (int)$quantity,
                'slug' => $product['slug'],
                'delivery_price' => $product['delivery_price'] * $this->currency_model->currencies[$product['currency_id']]['value'],
                'price' => format_currency($price,false),
                'name' => mb_strlen($product['name']) == 0 ? 'no name' : mb_ereg_replace("[^a-zA-ZА-Яа-я0-9\s]", "", $product['name']),
                'excerpt' => $product['excerpt'],
                'sku' => $product['sku'],
                'brand' => $product['brand'],
                'product_id' => (int)$product['id'],
                'supplier_id' => (int)$product['supplier_id'],
                'term' => (int)$product['term'],
                'is_stock' => (bool)$this->supplier_model->suppliers[$supplier_id]['stock'],
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->supplier_model->suppliers[$supplier_id]['stock']){
                $quan_in_cart = key_exists(md5($cartId), $this->cart->contents()) ? $this->cart->contents()[md5($cartId)]['qty'] : 0;

                if ($product['quantity'] < $quantity + $quan_in_cart) {
                    $json['error'] = lang('text_error_qty_cart_add');
                    unset($data);
                }
            }
        }

        if (isset($data) && $this->cart->insert($data)) {
            $json['cartId'] = $cartId;
            $json['success'] = lang('text_success_cart');
            $json['product_count'] = $this->cart->total_items();
            $json['cart_amunt'] = format_currency($this->cart->total());
        }

        return $json;
    }
}