<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
require_once('bootstrap.php');

$categories = get_categories($connection);

$footer = include_template('footer.php', ['categories' => $categories]);
$layout = include_template('404.php', [
    'title' => 'Страница не найдена',
    'categories' => $categories,
    'footer' => $footer
]);

print($layout);
