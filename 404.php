<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
require_once('bootstrap.php');

$categories = get_categories($connection);

$main_page = include_template('404.php');
$footer = include_template('footer.php', ['categories' => $categories]);
$layout = include_template('layout2.php', [
    'categories' => $categories,
    'content' => $main_page,
    'title' => 'Страница не найдена',
    'footer' => $footer,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print($layout);
