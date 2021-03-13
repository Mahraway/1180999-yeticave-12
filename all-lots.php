<?php
/**
 * @var mysqli $connection
 * @var string $main_menu
 * @var string $title
 * @var int $config
 */

require_once __DIR__ . '/bootstrap.php';

$categories = get_categories($connection);
$lots_per_page = $config['pagination']['lots_per_page'];
$message = 'Все лоты в категории ';

if (!isset($_GET['category'])) {
    header('Location: /404.php');
    exit();
}
$category = $_GET['category'];
$current_page_number = get_current_page_number($_GET);
$count_total_founded_lots = get_count_all_lots($connection, $category);
$total_pages_count = calculate_total_page_count($count_total_founded_lots, $lots_per_page);
$lots = get_lots_by_category($connection, $category, $lots_per_page, $current_page_number);

if (!$lots) {
    header('Location: /404.php');
    exit();
}

$main_menu = include_template('/menu/top_menu.php', ['categories' => $categories]);
$main_page = include_template('all-lots.php', [
    'lots' => $lots,
    'category' => $category,
    'categories' => $categories,
    'message' => $message,
    'connection' => $connection,
    'total_pages_count' => $total_pages_count,
    'count_total_founded_lots' => $count_total_founded_lots,
    'current_page_number' => $current_page_number,
    'lots_per_page' => $lots_per_page
]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'top_menu' => $main_menu,
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title
]);

print($layout_content);
