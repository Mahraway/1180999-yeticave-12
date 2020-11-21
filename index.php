<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set("Europe/Moscow");
include_once('helpers.php');

$data_base = mysqli_connect("localhost", "root", "root", "yeticave");
mysqli_set_charset($data_base, "UTF8");

$lots = 
    "SELECT l.id, l.name, l.price, MAX(b.price) AS current_price , image, c.name AS category_name, l.dt_end
    FROM lots l
    JOIN categories c ON c.id = l.category_id
    LEFT JOIN bets b ON b.lot_id = l.id
    WHERE l.dt_end > NOW()
    GROUP BY (l.id)
    ORDER BY l.dt_add DESC";
$result = mysqli_query($data_base, $lots);
$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

$categories = 
    "SELECT id, name, code 
    FROM categories";
$result = mysqli_query($data_base, $categories);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$is_auth = rand(0, 1); // флаг авторизации
$user_name = 'Рашид'; // укажите здесь ваше имя
$title = 'YetiCave'; // Заголовок страницы

$main_page = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth
    ]
);

print($layout_content);

