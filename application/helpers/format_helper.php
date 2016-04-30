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
    if($term >= 24){
        $day = $term / 24;
        return $day.lang('text_day');
    }

    return $term.lang('text_time');
}

function format_category($categories){
    $return = '<ul>';
    foreach ($categories as $category){
        $return .= '<li><a href="/category/'.$category['slug'].'">'.$category['name'].'</a>';
        if($category['brands']){
            $return .= '<ul>';
            foreach ($category['brands'] as $brand){
                $return .= '<li><a href="/category/'.$category['slug'].'/brand/'.$brand['brand'].'">'.$brand['brand'].'</a>';
            }
            $return .= '</ul>';
        }
        if($category['children']){
            format_category($category['children']);
        }
        $return .= '</li>';

    }
    $return .= '</ul>';
    return $return;

}