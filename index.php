<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set("Europe/Moscow");
include_once('helpers.php');
include_once('data.php');

$is_auth = rand(0, 1); // флаг авторизации
$user_name = 'Рашид'; // укажите здесь ваше имя
$title = 'YetiCave'; // Заголовок страницы


$main_page = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth
    ]
);

print($layout_content);

