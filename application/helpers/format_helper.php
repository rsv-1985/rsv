<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

function format_currency($value, $return_text = true){
    $CI = &get_instance();
    $formated =  round_out($value,$CI->currency_model->default_currency['decimal_place']);
    if($return_text){
        return
            $CI->currency_model->default_currency['symbol_left']
            .$formated
            .$CI->currency_model->default_currency['symbol_right'];
    }else{
        return $formated;
    }

}

function round_out ($value, $places=0) {
    if ($places < 0) { $places = 0; }
    $mult = pow(10, $places);
    return ($value >= 0 ? ceil($value * $mult):floor($value * $mult)) / $mult;
}

function format_quantity($value){
    if($value > 5){
        return '>5'.lang('text_st');
    }else{
        return $value.lang('text_st');
    }
}

function format_term_class($term){
    if($term == 0){
        $class = 'in_stock';
    }else if($term < 24){
        $class = 'one_day';
    }else{
        $class = '';
    }
    return $class;
}

function format_term($term){
    $CI = &get_instance();
    if(@$CI->options['check_day_off']){
        $to_day = getdate();
        switch ($to_day['wday']){
            case 6:
                $term += 48;
                break;
            case 0:
                $term += 24;
                break;
        }
    }
    if($term >= 24){
        $day = $term / 24;
        return (int)$day.lang('text_day');
    }elseif($term == 0){
        return lang('text_in_stock');
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

function plural_form($number, $after) {
    /* варианты написания для количества 1, 2 и 5 */
    $cases = array (2, 0, 1, 1, 1, 2);
    return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}

function format_data($data){
    return substr($data, 0, 4)."-".substr($data, 4, 2);
}

function format_phone($phone){
    return preg_replace('/[^0-9]/','',$phone);
}

function format_balance($balance){
    if($balance < 0){
        return '<b title="Баланс" class="text-danger">'.round_out($balance,2).'</b>';
    }else{
        return '<b title="Баланс" class="text-success">'.round_out($balance,2).'</b>';
    }
}

function format_date($date){
    return date(lang('format_date'),strtotime($date));
}

function format_time($date){
    return date(lang('format_time'),strtotime($date));
}

function build_tree(array $elements, $parentId = 10001){

    $branch = array();

    foreach ($elements as $element) {
        if ($element['ID_parent'] == $parentId) {
            $children = build_tree($elements, $element['ID_tree']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[$element['ID_tree']] = $element;
            unset($elements[$element['ID_tree']]);
        }
    }

    return $branch;
}

function doOutputList($TreeArray, $sub = false)
{
    if($sub){
        $output = '<ul class="sub">';
    }else{
        $output = '<ul class="list-unstyled trees">';
    }


    foreach ($TreeArray as $tree){
        if(@$tree['children']){
            $output .= '<li>';
            $output .= '<i class="fa fa-plus-square-o"></i> <a href="#">'.$tree['Name'].'</a>';
            $output .= doOutputList($tree['children'], true);
            $output .= '</li>';
        }else{
            $output .= '<li>';
            $output .= '<a href="?id_tree='.$tree['ID_tree'].'">'.$tree['Name'].'</a>';
            $output .= '</li>';
        }

    }
    return $output .= '</ul>';
}

