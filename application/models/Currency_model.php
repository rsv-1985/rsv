<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Currency_model extends Default_model{
    public $table = 'currency';
    public $currencies;
    public $default_currency;

    public function __construct()
    {
        parent::__construct();
        $this->currencies = $this->get_currencies();
        $this->default_currency = $this->get_default();
    }

    public function get_currencies(){
        $rates = [];
        $currency = $this->get_all();
        foreach ($currency as $cur) {
            $rates[$cur['id']] = $cur;
        }
        return $rates;
    }

    public function get_default(){
        $default = false;
        foreach ($this->currencies as $currency_id => $currency){
            if($currency['value'] <= 1){
                $default = $currency;
            }
        }

        return $default;
    }

    public function currency_get_all(){
        $results = $this->db->get($this->table)->result_array();
        $return = [];
        foreach($results as $result){
            $return[$result['id']] = $result;
        }
        return $return;
    }
}