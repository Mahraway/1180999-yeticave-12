<?php
/**
 * Описание переменных
 * @var mysqli $connection ресурс соединения БД
 * @var string $title заголовок страницы
 */

header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");

require_once __DIR__ . '/bootstrap.php';

$categories = get_categories($connection);

$main_menu = include_template('menu/menu.php', ['categories' => $categories]);
$main_page = include_template('404.php');
$footer = include_template('footer.php', ['categories' => $categories, 'main_menu' => $main_menu]);
$layout = include_template('layout.php', [
    'main_menu' => $main_menu,
    'categories' => $categories,
    'content' => $main_page,
    'title' => $title . ' | Страница не найдена',
    'footer' => $footer
]);

print($layout);
