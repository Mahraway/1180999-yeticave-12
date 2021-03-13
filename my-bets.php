<?php
/**
 * @var mysqli $connection
 * @var string $title
 */

include_once __DIR__ . '/bootstrap.php';

$categories = get_categories($connection);
$bets = get_my_bets($connection, $_SESSION['user']['id']);

$main_menu = include_template('/menu/top_menu.php', ['categories' => $categories]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$main_page = include_template('my-bets.php', [
    'bets' => $bets,
    'connection' => $connection,
    'categories' => $categories
]);
$layout_content = include_template('layout.php', [
    'title' => $title . ' | Мои ставки',
    'content' => $main_page,
    'top_menu' => $main_menu,
    'footer' => $main_footer
]);

print($layout_content);
