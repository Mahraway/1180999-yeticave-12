<?php
require_once 'bootstrap.php';

$categories = get_categories($connection);
$lots = get_active_lots($connection);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form_data = filter_form_fields($_POST);
    $errors = validate_lot_form($form_data);

    if (empty($errors)) {
        $form_data['image_url'] = upload_file($_FILES);
        $id = add_lot($connection, $form_data);
        header("Location: /lot.php?id=$id");
    }
}

$main_page = include_template('add.php', ['error' => $errors, 'categories' => $categories]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout2.php', [
    'categories' => $categories,
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'error' => $errors,
]);

print ($layout_content);
