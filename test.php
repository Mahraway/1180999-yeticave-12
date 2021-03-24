<?php

require_once __DIR__ . '/bootstrap.php';

echo 'Когда дата из будущего ожидаем: пустую строку' . '<br />';
$time = date('Y-m-d H:i:s', time()+1);
$time = get_correct_timer($time);
var_dump($time);
if ($time === '') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда дата текущая, ожидаем : "Только что"' . '<br />';
$time = date('Y-m-d H:i:s', time());
$s = strtotime($time) - time();
$time = get_correct_timer($time);
var_dump($time);
if ($time === 'Только что') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло 30 сек, ожидаем : "30 секунд назад"' . '<br />';
$time = date('Y-m-d H:i:s', time()-30);
$time = get_correct_timer($time);
var_dump($time);
if ($time === '30 секунд назад') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло 60 сек, ожидаем : "1 минуту назад"' . '<br />';
$time = date('Y-m-d H:i:s', time()-60);
$time = get_correct_timer($time);
var_dump($time);
if ($time === '1 минуту назад') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло 30 мин, ожидаем : "30 минут назад"' . '<br />';
$time = date('Y-m-d H:i:s', time()-1800);
$time = get_correct_timer($time);
var_dump($time);
if ($time === '30 минут назад') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло 60 мин, ожидаем : "1 час назад"' . '<br />';
$time = date('Y-m-d H:i:s', time()-3600);
$time = get_correct_timer($time);
var_dump($time);
if ($time === '1 час назад') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло 23 часа, ожидаем : "23 часа назад"' . '<br />';
$time = date('Y-m-d H:i:s', time()-3600*23);
$time = get_correct_timer($time);
var_dump($time);
if ($time === '23 часа назад') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло >= 24 часа, но < 48 часов ожидаем : "Вчера"' . '<br />';
$time = date('Y-m-d H:i:s', time()-(3600*24));
$time = get_correct_timer($time);
var_dump($time);
if ($time === 'Вчера') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло 30 часов ожидаем : "Вчера"' . '<br />';
$time = date('Y-m-d H:i:s', time()-(3600*30));
$time = get_correct_timer($time);
var_dump($time);
if ($time === 'Вчера') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло 47 часов ожидаем : "Прошло более двух суток"' . '<br />';
$time = date('Y-m-d H:i:s', time()-(3600*47));
$time = get_correct_timer($time);
var_dump($time);
if ($time === 'Прошло более двух суток') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло 48 часов ожидаем : "Прошло более двух суток"' . '<br />';
$time = date('Y-m-d H:i:s', time()-(3600*48));
$time = get_correct_timer($time);
var_dump($time);
if ($time === 'Прошло более двух суток') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}

echo '<hr />' . 'Когда прошло 50 часов ожидаем : "Прошло более двух суток"' . '<br />';
$time = date('Y-m-d H:i:s', time()-(3600*50));
$time = get_correct_timer($time);
var_dump($time);
if ($time === 'Прошло более двух суток') {
    echo '<b>Успешно</b>';
} else {
    echo 'Ошибка';
}
