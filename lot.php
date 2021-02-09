<?php
require_once('bootstrap.php');

$id = get_param_id($_GET['id']);

if (!$id) {
    header('Location: /404.php');
}

$categories = get_categories($connection);
$lot = get_lot($connection,$id);

if (!$lot) {
    header('Location: /404.php');
}
$main_page = include_template('lot.php',['lot' => $lot, 'categories' => $categories]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout2.php', [
        'content' => $main_page,
        'categories' => $categories,
        'footer' => $main_footer,
        'title' => $title,
        'user_name' => $user_name,
        'is_auth' => $is_auth,
    ]
);

print($layout_content);

