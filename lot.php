<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set("Europe/Moscow");

include_once('helpers.php');
include_once('queries.php');

if (!empty ($_GET['id'])) {
    $id = $_GET['id'];
} else include('404.php');

include_once('config.php');
$connection = db_connect($dbHost, $dbUser, $dbPassword, $dbDatabase);
$categories = get_categories($connection);
$lot = get_lot($id, $connection);

$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('lot.php', [
    'lot' => $lot,
    'categories' => $categories,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    ]
);

print($layout_content);

