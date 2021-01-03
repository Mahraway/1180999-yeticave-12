<?php
require_once 'bootstrap.php';

$categories = get_categories($connection);
$lots = get_active_lots($connection);

$main_footer = include_template('footer.php', ['categories' => $categories]);
$selected ='';

if (isset($_POST['my_form'])) {
    if (isset($_FILES['lot-img'])) {
        $file_name = $_FILES['lot-img']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;
        move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
    } else {
        $file_url = '';
    }
    $lot = [
        'user' => 1,
        'category' => (int) $_POST['category'],
        'name' => htmlspecialchars($_POST['lot-name']),
        'message' => htmlspecialchars($_POST['message']),
        'img_url' => $file_url,
        'price' => (int) $_POST['lot-rate'],
        'dt_end' => htmlspecialchars($_POST['lot-date']),
        'step' => (int) $_POST['lot-step']
    ];
    add_lot($connection, $lot);
}

$add_lot_page = include_template('add.php', [
    'categories' => $categories,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'selected' => $selected
]);

print ($add_lot_page);
