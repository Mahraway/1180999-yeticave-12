<?php

/**
 * Функция проверки подлкючения К БД
 * Ограничения: функция принимает четыре аргумента - хост, имя пользователя, пароль и имя БД
 * @param array $db_config данные для соединения с БД
 * @return mysqli в случае успеха возвращает идентификатор соединения
 * */
function db_connect(array $db_config): mysqli
{
    $connection = mysqli_connect(
        $db_config['host'],
        $db_config['user'],
        $db_config['password'],
        $db_config['database']
    );

    if (!$connection) {
        exit('<br>Соединение не удалось: '. mysqli_connect_error());
    }
    mysqli_set_charset($connection, "UTF8");
    return $connection;
}

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
 * @return int в случае успеха, возвращает id добавленного лота
 */
function add_lot(mysqli $connection, array $lot): int
{

    $sql = "INSERT INTO lots (user_id, category_id, dt_add, name, description, image, price, dt_end, step)
            VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $data = [
        $lot['user_id'],
        $lot['category_id'],
        date('Y:m:d h:i:s'),
        $lot['name'],
        $lot['description'],
        $lot['image_url'],
        $lot['price'],
        $lot['dt_end'],
        $lot['step']
    ];

    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    $res = mysqli_stmt_execute($stmt);

    if (!$res) {
        exit('Ошибка: '. mysqli_error($connection));
    }

    return mysqli_insert_id($connection);
}

/**
 * Добавляет нового пользователя в базу данных
 * @param mysqli $connection идентификатор соединения
 * @param array $user данные о пользователе
 */
function add_user(mysqli $connection, array $user) : void
{
    $sql = "INSERT INTO users (dt_add, name, email, pass, contacts)
            VALUES ( ?, ?, ?, ?, ?)";

    $data = [
        date('Y:m:d h:i:s'),
        $user['name'],
        $user['email'],
        $user['password'],
        $user['contacts']
    ];

    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    $res = mysqli_stmt_execute($stmt);

    if (!$res) {
        exit('Ошибка: '. mysqli_error($connection));
    }
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt(mysqli $link,string $sql,array $data = []) : mysqli_stmt
{
    $stmt = mysqli_prepare($link, $sql);

    if (!$stmt) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает массив с данными пользователя по e-mail
 * @param mysqli $connection идентификатор соединения БД
 * @param string $email проверяемый емайл
 * @return array|null возвращает массив с данными о пользователе
 */
function get_user_by_email(mysqli $connection, string $email): ?array
{
    $sql = "SELECT * FROM `users` WHERE email = ?";

    $data = [$email];

    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Формирует список лотов для текущей страницы
 * @param mysqli $connection
 * @param int $lots_per_page
 * @param int $current_page
 * @param string $search
 * @return array
 */
function search_lots(mysqli $connection, string $search, int $lots_per_page, int $current_page) : array
{
    $offset = ($current_page - 1) * $lots_per_page;

    $sql = "SELECT *, MATCH(name,description) AGAINST(? IN NATURAL LANGUAGE MODE) AS score
            FROM lots
            WHERE MATCH(name,description) AGAINST(? IN NATURAL LANGUAGE MODE)
            LIMIT ? OFFSET ?";

    $data = [$search, $search, $lots_per_page, $offset];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает количество найденных лотов
 * @param mysqli $connection
 * @param string $search
 * @return int
 */
function get_count_total_founded_lots(mysqli $connection, string $search) : int
{

    $sql = "SELECT COUNT(*)
            FROM lots
            WHERE MATCH(name,description) AGAINST(? IN NATURAL LANGUAGE MODE)";

    $data = [$search];

    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }
    $count = mysqli_fetch_assoc($result);

    return (int) $count['COUNT(*)'];
}

/**
 * @param mysqli $connection
 * @param int $user_id
 * @param int $lot_id
 * @param int $price
 */
function add_bet(mysqli $connection, int $user_id, int $lot_id, int $price) : void
{
    $sql = "INSERT INTO bets (user_id, lot_id, dt_add, price)
            VALUES (?, ?, ?, ?)";
    $data = [
        $user_id,
        $lot_id,
        date('Y:m:d H:i:s'),
        $price
    ];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }
}

/**
 * @param mysqli $connection
 * @param int $lot_id
 * @param int $price
 */
function update_lot_price(mysqli $connection, int $lot_id, int $price) : void
{
    $sql = "UPDATE lots SET price = ? WHERE lots.id = ?";
    $data = [
        $price,
        $lot_id
    ];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }
}

/**
 * @param mysqli $connection
 * @param int $user
 * @return array
 */
function get_my_bets(mysqli $connection, int $user) : array
{
    $sql = "SELECT * FROM bets WHERE user_id=? ORDER BY dt_add DESC";
    $data = [$user];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * @param mysqli $connection
 * @param int $lot
 * @return array
 */
function get_bets_by_lot(mysqli $connection, int $lot) : array
{
    $sql = "SELECT * FROM bets WHERE lot_id=? ORDER BY dt_add DESC";
    $data = [$lot];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
