<?php

class MY_Cart extends CI_Cart {
    public $CI;

    public function __construct(){
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->model('cart_model');
    }

    public function destroy()
    {
        $this->_cart_contents = array('cart_total' => 0, 'total_items' => 0);
        $this->CI->session->unset_userdata('cart_contents');
        //Если пользователь залогинен, сохраняем корзину в базу данных
        if($this->CI->is_login){
            $this->CI->cart_model->cart_clear($this->CI->is_login);
        }
    }

    public function set_cart_contents($cart_contents){
        $this->_cart_contents = $cart_contents;
        $this->_save_cart();
    }

    protected function _save_cart()
    {

        // Let's add up the individual prices and set the cart sub-total
        $this->_cart_contents['total_items'] = $this->_cart_contents['cart_total'] = 0;
        foreach ($this->_cart_contents as $key => $val)
        {
            // We make sure the array contains the proper indexes
            if ( ! is_array($val) OR ! isset($val['price'], $val['qty']))
            {
                continue;
            }

            $this->_cart_contents['cart_total'] += ($val['price'] * $val['qty']);
            $this->_cart_contents['total_items'] += $val['qty'];
            $this->_cart_contents[$key]['subtotal'] = ($this->_cart_contents[$key]['price'] * $this->_cart_contents[$key]['qty']);
        }

        // Is our cart empty? If so we delete it from the session
        if (count($this->_cart_contents) <= 2)
        {
            $this->CI->session->unset_userdata('cart_contents');
            //Если пользователь залогинен, сохраняем корзину в базу данных
            if($this->CI->is_login){
                $this->CI->cart_model->cart_clear($this->CI->is_login);
            }
            // Nothing more to do... coffee time!
            return FALSE;
        }

        // If we made it this far it means that our cart has data.
        // Let's pass it to the Session class so it can be stored
        $this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));

        //Если пользователь залогинен, сохраняем корзину в базу данных
        if(@$this->CI->is_login){
            $this->CI->cart_model->cart_insert(serialize($this->_cart_contents),$this->CI->is_login);
        }

        // Woot!
        return TRUE;
    }
}