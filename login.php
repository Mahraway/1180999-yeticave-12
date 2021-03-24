<?php
/**
 * @var mysqli $connection идентификатор соединени БД
 * @var string $main_menu шаблон главного меню
 * @var string $title заголовок страницы
 * @var array $form_data отфильтрованные данные формы
 */

require_once __DIR__ . '/bootstrap.php';

$categories = get_categories($connection);
$errors = [];
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form_data = filter_form_fields($_POST);
    $errors = validate_login_form($connection, $form_data);

    if (empty($errors)) {
        $_SESSION['user'] = get_user_by_email($connection, $form_data['email']);
        header('Location: /index.php');
        exit();
    }
}

$main_menu = include_template('/menu/menu.php', ['categories' => $categories]);
$main_page = include_template('login.php', ['error' => $errors, 'form_data' => $form_data]);
$main_footer = include_template('footer.php', ['categories' => $categories, 'main_menu' => $main_menu]);
$layout_content = include_template('layout.php', [
    'content' => $main_page,
    'main_menu' => $main_menu,
    'footer' => $main_footer,
    'title' => $title . ' | Войти в систему'
]);

print($layout_content);
