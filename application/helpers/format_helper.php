<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

function format_currency($value){
    $CI = &get_instance();
    return
        $CI->default_currency['symbol_left']
        .round($value,$CI->default_currency['decimal_place'])
        .$CI->default_currency['symbol_right'];
}

function format_quantity($value){
    if($value > 5){
        return '>5'.lang('text_st');
    }else{
        return $value.lang('text_st');
    }
}

function format_term($term){
   return $term.lang('text_time');
}