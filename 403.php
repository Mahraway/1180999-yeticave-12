<?php
/**
 * Описание переменных
 * @var mysqli $connection ресурс соединения БД
 * @var string $title заголовок страницы
 */

header("HTTP/1.1 403 Forbidden");
header("Status: 403 Forbidden");

require_once __DIR__ . '/bootstrap.php';

$categories = get_categories($connection);

$main_menu = include_template('menu/menu.php', ['categories' => $categories]);
$main_page = include_template('403.php');
$footer = include_template('footer.php', ['categories' => $categories, 'main_menu' => $main_menu]);
$layout = include_template('layout.php', [
    'main_menu' => $main_menu,
    'categories' => $categories,
    'content' => $main_page,
    'title' => $title . ' | Доступ запрещен',
    'footer' => $footer
]);

print($layout);
