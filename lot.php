<?php
ob_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set("Europe/Moscow");

include_once('helpers.php');
include_once('queries.php');
include_once('config.php');

$connection = db_connect($dbHost, $dbUser, $dbPassword, $dbDatabase);
$lots = get_all_lots($connection); 
$categories = get_categories($connection);


if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: /pages/404.html");
    exit;
}

$lot = get_lot($id, $connection);

if (!in_array($id, array_column($lots, 'id'))) {
    header("Location: /pages/404.html");
    exit;
}

$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('lot.php', [
    'lots' => $lots,
    'lot' => $lot,
    'categories' => $categories,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    ]
);

print($layout_content);

