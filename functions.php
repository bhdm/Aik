<?php

function getWeekName($number){
    switch ($number){
        case 1: return 'Понедельник';
        case 2: return 'Вторник';
        case 3: return 'Среда';
        case 4: return 'Четверг';
        case 5: return 'Пятница';
        case 6: return 'Суббота';
        case 7: return 'Воскресенье';
    }
}

function getMonthName($number){
    switch ($number){
        case 1: return  'Январь';
        case 2: return  'Февраль';
        case 3: return  'Март';
        case 4: return  'Апрель';
        case 5: return  'Май';
        case 6: return  'Июнь';
        case 7: return  'Июль';
        case 8: return  'Август';
        case 9: return  'Сентябрь';
        case 10: return 'Октябрь';
        case 11: return 'Ноябрь';
        case 12: return 'Декабрь';
    }
}

function getMonthShortName($number){
    switch ($number){
        case 1: return  'Янв';
        case 2: return  'Фев';
        case 3: return  'Мар';
        case 4: return  'Апр';
        case 5: return  'Май';
        case 6: return  'Июн';
        case 7: return  'Июл';
        case 8: return  'Авг';
        case 9: return  'Сен';
        case 10: return 'Окт';
        case 11: return 'Ноя';
        case 12: return 'Дек';
    }
}
global $t;
$t = 0;
function mergeArray($array1, $array2){
    global $t;
//    echo $t;
    if ($t == 2){
//        echo '';
    }
    $t ++;
    $data = array();
    if ($array2 == null){
        return $array1;
    }
    foreach($array2 as $key => $val){
        if (isset($array1[$key])){
            if (is_array($val)){
                $data[$key] = mergeArray($array1[$key], $val);
            }else{
                $data[$key] = $val + $array1[$key];
            }
        }else{
            $data[$key] = $val;
        }
    }
    return $data;
}
