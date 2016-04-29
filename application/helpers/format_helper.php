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

function build_tree($cats,$parent_id,$only_parent = false){
    if(is_array($cats) and isset($cats[$parent_id])){
        if($parent_id == 0){
            $tree = '<ul class="dropdown-menu categories" role="menu" aria-labelledby="dropdownMenu">';
        }else{
            $tree = ' <ul class="dropdown-menu"><span class="block_cat">';
        }

        if($only_parent==false){
            foreach($cats[$parent_id] as $cat){
                $tree .= '<li><a href="/category/'.$cat['slug'].'">'.$cat['name'].'</a>';
                $tree .=  build_tree($cats,$cat['id']);
                $tree .= '</li>';
            }
        }elseif(is_numeric($only_parent)){
            $cat = $cats[$parent_id][$only_parent];
            $tree .= '<li>'.$cat['name'].' #'.$cat['id'];
            $tree .=  build_tree($cats,$cat['id']);
            $tree .= '</li>';
        }
        if($parent_id == 0){
            $tree .= '</ul>';
        }else{
            $tree .= '</span></ul>';
        }

    }
    else return null;
    return $tree;
}