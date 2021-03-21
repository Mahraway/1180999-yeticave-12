<?php
/**
 * Описание переменных
 * @var mysqli $connection идентификатор соединения БД
 * @var string $title заголовок страницы
 */

include_once __DIR__ . '/bootstrap.php';

$categories = get_categories($connection);
$bets = get_my_bets($connection, $_SESSION['user']['id']);

$main_menu = include_template('/menu/menu.php', ['categories' => $categories]);
$main_footer = include_template('footer.php', ['categories' => $categories, 'main_menu' => $main_menu]);
$main_page = include_template('my-bets.php', [
    'bets' => $bets,
    'connection' => $connection,
    'categories' => $categories
]);
$layout_content = include_template('layout.php', [
    'main_menu' => $main_menu,
    'title' => $title . ' | Мои ставки',
    'content' => $main_page,
    'footer' => $main_footer
]);

print($layout_content);
