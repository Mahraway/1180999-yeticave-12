<?php
/**
 * Описание переменных
 * @var mysqli $connection идентификатор соединения
 * @var string $title заголовок страницы
 * @var array $form_data отфильтрованные данные формы
 */

require_once __DIR__ . '/bootstrap.php';

if (!isset($_SESSION['user'])) {
    header('Location: /403.php');
    exit();
}

$categories = get_categories($connection);
$lots = get_active_lots($connection);

$errors = [];
$form_data = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form_data = filter_form_lot($_POST);
    $errors = validate_lot_form($form_data, $_FILES);

    if (empty($errors)) {
        $form_data['image_url'] = upload_file($_FILES);
        $form_data['user_id'] = $_SESSION['user']['id'];
        $id = add_lot($connection, $form_data);
        header("Location: /lot.php?id=$id");
        exit();
    }
}

$main_menu = include_template('/menu/menu.php', ['categories' => $categories]);
$main_page = include_template('add.php', [
    'error' => $errors,
    'categories' => $categories,
    'form_data' => $form_data
]);
$main_footer = include_template('footer.php', ['categories' => $categories, 'main_menu' => $main_menu]);
$layout_content = include_template('layout.php', [
    'main_menu' => $main_menu,
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title . ' | Добавить лот'
]);

print ($layout_content);
