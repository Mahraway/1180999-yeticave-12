<?php
require_once 'bootstrap.php';

function get_lots_by_category(mysqli $connection, int $category_id): ?array
{
        $sql = "SELECT * FROM lots WHERE category_id = '$category_id'";
        $res = mysqli_query($connection, $sql);
        if (!$res) {
            exit('Ошибка' . mysqli_error($connection));
        }

        return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

$category_id = get_param_id($_GET['category_id']);

if (!$category_id) {
    header('Location: /404.php');
}

$lots = get_lots_by_category($connection, $category_id);
$categories = get_categories($connection);

if (!$lots) {
    header('Location: 404.php');
}


$main_menu = include_template('/menu/top_menu.php', ['categories' => $categories]);
$main_page = include_template('test.php', ['lots' => $lots]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout.php',[
    'top_menu' => $main_menu,
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print ($layout_content);
