<?php
require_once 'bootstrap.php';

$categories = get_categories($connection);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form_data = filter_form_fields($_POST);
    $errors = validate_reg_form($connection, $form_data);

    if (empty($errors)) {
        $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
        add_user($connection, $form_data);
        header("Location: /pages/login.html");
    }
}

$main_page = include_template('sign-up.php', ['error' => $errors]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout2.php', [
    'categories' => $categories,
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print ($layout_content);

