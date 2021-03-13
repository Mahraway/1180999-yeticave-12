<?php
/**
 * Описание переменных
 * @var mysqli $connection идентификатор соединения БД
 */

header("HTTP/1.1 403 Forbidden");
header("Status: 403 Forbidden");

require_once __DIR__ . '/bootstrap.php';

$categories = get_categories($connection);

$menu = include_template('promo_menu.php', ['categories' => $categories]);
$main_page = include_template('403.php');
$footer = include_template('footer.php', ['categories' => $categories]);
$layout = include_template('layout.php', [
    'main_menu' => $menu,
    'categories' => $categories,
    'content' => $main_page,
    'title' => 'Доступ запрещен',
    'footer' => $footer
]);

print($layout);
