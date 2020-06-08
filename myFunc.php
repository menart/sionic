<?php
function search_array($value,array $array){
    foreach($array as $field){
        if($value->equals($field)){
            return $field;
        }
    }
    return false;
}