<?php

require_once 'bootstrap.php';

$lots_per_page = $config['pagination']['lots_per_page'];

$search = '2015';
$current_page = $_GET['page'];

echo $count_total_founded_lots = get_count_total_founded_lots($connection, $search);
//    Получить общее количество страниц: всего записей / записей на страницу;
//
echo '<br>' . $last_page_number = ceil($count_total_founded_lots / $lots_per_page);

$lots = search_lots($connection, $search, $lots_per_page, $current_page);

print_r($lots);
