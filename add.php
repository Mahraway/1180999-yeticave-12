<?php
/**
 * @var mysqli $connection идентификатор соединения
 * @var string $title заголовок страницы
 * @var string $user_name имя пользователя
 * @var int $is_auth флаг авторизации
 */

require_once __DIR__ . '/bootstrap.php';

$categories = get_categories($connection);
$lots = get_active_lots($connection);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form_data = filter_form_lot($_POST);
    $errors = validate_lot_form($form_data, $_FILES);

    if (empty($errors)) {
        $form_data['image_url'] = upload_file($_FILES);
        $id = add_lot($connection, $form_data);
        header("Location: /lot.php?id=$id");
    }
}

$main_menu = include_template('/menu/top_menu.php', ['categories' => $categories]);
$main_page = include_template('add.php', ['error' => $errors, 'categories' => $categories]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'top_menu' => $main_menu,
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print ($layout_content);
