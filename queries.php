<?php
/**
 * Функция формирования самых новых и активных лотов на главной странице
 * @param mysqli $connection - идентификатор соединения с БД
 * @return array $lots - ассоциативный массив с содержимым лотов
 */
function get_active_lots(mysqli $connection): array
{
    $lots =
        "SELECT l.id, l.name, l.price, MAX(b.price) AS current_price , image, c.name AS category_name, l.dt_end
        FROM lots l
        JOIN categories c ON c.id = l.category_id
        LEFT JOIN bets b ON b.lot_id = l.id
        WHERE l.dt_end > NOW()
        GROUP BY (l.id)
        ORDER BY l.dt_add DESC";
    $result = mysqli_query($connection, $lots);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lots;
}

/**
 * Функция формирования категорий товаров
 * @param mysqli $connection - идентификатор соединения с БД
 * @return array $categories - ассоциативный массив с списком категорий
 */
function get_categories(mysqli $connection): array
{
    $categories =
        "SELECT id, name, code
        FROM categories";
    $result = mysqli_query($connection, $categories);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $categories;
}

/**
 * Функция возвращает информацию о лоте по id, а так же проверяем существование лота в БД
 * @param int $id - идентифиактор лота
 * @param mysqli $connection - идентифиактор соединения с БД
 * @return array - одномерный массив с данными о лоте
 */
function get_lot(mysqli $connection, int $id): ?array
{
    $lot =
        "SELECT *
        FROM lots
        WHERE id = $id";

    $result = mysqli_query($connection, $lot);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }

    $lot = mysqli_fetch_assoc($result);

    return $lot;
}

/**
 * Функция добавляет в базу данных новый лот
 * @param mysqli $connection идентифиактор соединения БД
 * @param $lot array массив с информацией о лоте
 * @return string в случае успеха, возвращает id добавленного лота
 */
function add_lot(mysqli $connection, array $lot): string
{
    $lot['user_id'] = 1;

    $add_query = "INSERT INTO lots (user_id, category_id, dt_add, name, description, image, price, dt_end, step)
    VALUES (
            '" . $lot['user_id'] . "',
            '" . $lot['category_id'] . "',
             NOW(),
            '" . $lot['name'] . "',
            '" . $lot['description'] . "',
            '" . $lot['image_url'] . "',
            '" . $lot['price'] . "',
            '" . $lot['dt_end'] . "',
            '" . $lot['step'] . "'
    );
";

    $result = mysqli_query($connection, $add_query);
    if (!$result) {
        exit('Ошибка ' . mysqli_error($connection));
    }

    return mysqli_insert_id($connection);
}


