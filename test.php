<?php
require_once 'bootstrap.php';

$categories = get_categories($connection);

function get_user(mysqli $connection, string $user_id): ?array
{
    if ($user_id) {
        $sql = "SELECT * FROM users;";
        $res = mysqli_query($connection, $sql);
        if (!$res) {
            exit('Ошибка' . mysqli_error($connection));
        }

        $res = mysqli_fetch_assoc($res);
        print_r($res);
        return $res;
    }
    header('Location: 404.php');
}


if (isset($_GET['id'])) {
    $id = get_param_id();
} else {
    header('Location: 404.php');
}

$user = get_user($connection, $_GET['id']);

if (!$user) {
    header('Location: 404.php');
}

$main_page = include_template('test.php', ['user' => $user]);
$main_footer = include_template('footer.php', ['categories' => $categories]);
$layout_content = include_template('layout2.php',[
    'categories' => $categories,
    'content' => $main_page,
    'footer' => $main_footer,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
]);

print ($layout_content);
