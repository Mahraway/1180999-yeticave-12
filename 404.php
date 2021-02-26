<?php
/**
 * Описание переменных
 * @var mysqli $connection идентификатор соединения БД
 */

header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");

require_once('bootstrap.php');

$categories = get_categories($connection);

$menu = include_template('promo_menu.php', ['categories' => $categories]);
$main_page = include_template('404.php');
$footer = include_template('footer.php', ['categories' => $categories]);
$layout = include_template('layout.php', [
    'main_menu' => $menu,
    'categories' => $categories,
    'content' => $main_page,
    'title' => 'Страница не найдена',
    'footer' => $footer
]);

print($layout);
