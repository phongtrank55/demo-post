<?php

function format_price($price='',$format=true){
    if($format) {
        if($price == 0) return '0₫';
        if($price != 0 && $price != '') return number_format($price, 0, ',', '.') . '₫';
        return number_format($price, 0, ',', '.') . '₫';
    }
    return $price;
}
