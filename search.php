<?php
/**
 * @var array $connection
 * @var string $title
 */

require_once 'bootstrap.php';


$categories = get_categories($connection);
$lots = [];
$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!$_GET['search']) {
        header('Location: /404.php');
        exit();
    }

    $search_form = filter_form_fields($_GET);

    !isset($_GET['page']) ? $_GET['page'] = 1 : null;

    $page = $_GET['page'];
    $limit = 9;
    $offset = 9 * ($page - 1);

    $lots = get_lots_from_search($connection, $limit, $offset, $search_form['search']);
    if (empty($lots)) {
        $message = 'Ничего не найдено по вашему запросу<hr />';
    }

    $page_count = ceil($_SERVER['page_count']/$limit);
}

$main_menu = include_template('/menu/top_menu.php', ['categories' => $categories]);
$main_page = include_template('search.php', [
    'lots' => $lots,
    'categories' => $categories,
    'message' => $message,
    'page_count' => $page_count
]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'top_menu' => $main_menu,
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title . '| Результаты поиска'
]);

print($layout_content);
