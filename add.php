<?php
require_once 'bootstrap.php';

$categories = get_categories($connection);
$lots = get_active_lots($connection);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $form_data = filter_form_fields($_POST);
    $errors = validate_lot_form($form_data);

    if (empty($errors)) {
        $_POST['image_url'] = upload_file($_FILES);
        $id = add_lot($connection, $_POST);
        header("Location: /lot.php?id=$id");
    }
}
$main_footer = include_template('footer.php', ['categories' => $categories]);
$add_lot_page = include_template('add.php', [
    'categories' => $categories,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'error' => $errors,
]);

print ($add_lot_page);
