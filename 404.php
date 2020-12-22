<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

requrie_once('helpers.php');
require_once('config.php');
require_once('queries.php');

$connection = db_connect($dbHost, $dbUser, $dbPassword, $dbDatabase);
$categories = get_categories($connection);

$footer = include_template('footer.php', ['categories' => $categories]);
$layout = include_template('404.php', [
    'title' => 'Страница не найдена',
    'categories' => $categories,
    'footer' => $footer
]);

print($layout);
