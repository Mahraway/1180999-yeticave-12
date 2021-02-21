<?php
/**
 * Описание переменных
 * @var mysqli $connection идентификатор соединения БД
 * @var string $title заголовок страницы
 */

require_once('bootstrap.php');

$id = get_param_id($_GET['id']);

if (!$id) {
    header('Location: /404.php');
    exit();
}

$categories = get_categories($connection);
$lot = get_lot($connection,$id);

if (!$lot) {
    header('Location: /404.php');
    exit();
}

$main_menu = include_template('/menu/top_menu.php', ['categories' => $categories]);
$main_page = include_template('lot.php',['lot' => $lot, 'categories' => $categories]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
        'top_menu' => $main_menu,
        'content' => $main_page,
        'categories' => $categories,
        'footer' => $main_footer,
        'title' => $title
    ]
);

print($layout_content);

