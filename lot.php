<?php
/**
 * Описание переменных
 * @var mysqli $connection идентификатор соединения БД
 * @var string $title заголовок страницы
 */

require_once __DIR__ . '/bootstrap.php';

if (!isset($_GET['id'])) {
    header('Location: 404.php');
    exit();
}
$id = get_param_id($_GET['id']);
$categories = get_categories($connection);
$lot = get_lot($connection,$id);

if (!$lot['id']) {
    header('Location: 404.php');
    exit();
}

$bets = get_bets_by_lot($connection, $lot['id']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_data = filter_form_fields($_POST);
    $error = validate_add_bet($connection, $form_data, $lot);
    if (!$error) {
        add_bet($connection, $_SESSION['user']['id'], $lot['id'], (int) $form_data['cost']);
        header('Location: /lot.php?id='.$lot['id']);
        exit();
    }
}

$main_menu = include_template('/menu/top_menu.php', ['categories' => $categories]);
$main_page = include_template('lot.php',[
    'bets' => $bets,
    'lot' => $lot,
    'categories' => $categories,
    'error' => $error,
    'connection' => $connection
]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
        'top_menu' => $main_menu,
        'content' => $main_page,
        'categories' => $categories,
        'footer' => $main_footer,
        'title' => $title
    ]
);

print($layout_content);

