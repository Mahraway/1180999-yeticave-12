<?php
/**
 * Описание переменных
 * @var mysqli $connection ресурс соединения БД
 * @var string $title заголовок страницы
 * @var string $content шаблон главной страницы
 * @var string $footer шаблон футера
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/get_winner.php';

$lots = get_active_lots($connection);
$categories = get_categories($connection);

$lots_list = include_template('lots_list.php', ['lots' => $lots, 'connection' => $connection]);
$promo_menu = include_template('/menu/promo_menu.php', ['categories' => $categories]);
$main_menu = include_template('/menu/menu.php', ['categories' => $categories]);
$main_page = include_template('main.php', ['promo_menu' => $promo_menu, 'lots_list' => $lots_list]);
$main_footer = include_template('footer.php', ['categories' => $categories, 'main_menu' => $main_menu]);
$layout_content = include_template('layout.php', [
        'content' => $main_page,
        'footer' => $main_footer,
        'title' => $title . ' | Интернет-аукцион сноубордического и горнолыжного снаряжения'
    ]
);

print($layout_content);
