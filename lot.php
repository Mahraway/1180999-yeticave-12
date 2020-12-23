<?php
require_once('bootstrap.php');

$id = getParamId($_GET);

if (!$id) {
    header('Location: /404.php');
}

$categories = get_categories($connection);
$lot = get_lot($connection,$id);

if (!$lot) {
    header('Location: /404.php');
}

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
