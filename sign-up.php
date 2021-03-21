<?php
/**
 * Описание переменных
 * @var mysqli $connection идентификатор соединения БД
 * @var string $title заголовок страницы
 */

require_once __DIR__ . '/bootstrap.php';

if (isset($_SESSION['user'])) {
    header('Location: /403.php');
}

$categories = get_categories($connection);
$errors = [];
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form_data = filter_form_fields($_POST);
    $errors = validate_registration_form($connection, $form_data);

    if (empty($errors)) {
        $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
        add_user($connection, $form_data);
        header("Location: /login.php");
    }
}

$main_menu = include_template('/menu/menu.php', ['categories' => $categories]);
$main_page = include_template('sign-up.php', ['error' => $errors, 'form_data' => $form_data]);
$main_footer = include_template('footer.php', ['categories' => $categories, 'main_menu' => $main_menu]);
$layout_content = include_template('layout.php', [
    'main_menu' => $main_menu,
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title
]);

print ($layout_content);

