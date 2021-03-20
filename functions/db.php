<?php

/**
 * Функция проверки подлкючения К БД
 * Ограничения: функция принимает четыре аргумента - хост, имя пользователя, пароль и имя БД
 * @param array $db_config данные для соединения с БД
 * @return mysqli в случае успеха возвращает ресурс соединения
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
 * @param mysqli $connection ресурс соединения
 * @return array возвращает массив с лотами
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
 * @param mysqli $connection ресурс соединения
 * @return array возвращает массив с категориями
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
 * @param int $id идентифиактор лота
 * @param mysqli $connection ресурс соединения
 * @return array массив с данными о лоте
 */
function get_lot(mysqli $connection, int $id): ?array
{
    $lot =
        "SELECT l.id, l.name, l.user_id, l.winner_id, l.step, l.description, l.price, MAX(b.price) AS current_price, image,
                c.name AS category_name, l.dt_end
        FROM lots l
        JOIN categories c ON c.id = l.category_id
        LEFT JOIN bets b ON b.lot_id = l.id
        WHERE l.id = $id";

    $result = mysqli_query($connection, $lot);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Функция добавляет в базу данных новый лот
 * @param mysqli $connection ресурс соединения
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
        date('Y:m:d H:i:s'),
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
 * @param mysqli $connection ресурс соединения
 * @param array массив с данными о пользователе
 */
function add_user(mysqli $connection, array $user) : void
{
    $sql = "INSERT INTO users (dt_add, name, email, password, contacts)
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
 * @param mysqli $connection Ресурс соединения
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
 * Формирует список лотов для текущей страницы поиска
 * @param mysqli $connection ресурс соединения
 * @param int $lots_per_page количество элементов на странице
 * @param int $current_page номер текущей страницы
 * @param string $search данные с формы поиска
 * @return array возвращает массив с лотами
 */
function search_lots(mysqli $connection, string $search, int $lots_per_page, int $current_page) : array
{
    $offset = ($current_page - 1) * $lots_per_page;

    $sql = "SELECT l.id, l.name, l.description, l.price, MAX(b.price) AS current_price, image,
                c.name AS category_name, l.dt_end, MATCH(l.name, l.description) AGAINST(? IN NATURAL LANGUAGE MODE) AS score
                FROM lots l
                JOIN categories c ON l.category_id = c.id
                LEFT JOIN bets b ON l.id = b.lot_id
                WHERE MATCH(l.name, l.description) AGAINST(? IN NATURAL LANGUAGE MODE)
                GROUP BY l.id
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
 * Функция подсчета лотов по полю поиска
 * @param mysqli $connection ресурс соединения
 * @param string $search данные с формы поиска
 * @return int возвращает количество найденных лотов
 */
function get_count_total_founded_lots_from_search(mysqli $connection, string $search) : int
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
 * Функция подсчета лотов в категории
 * @param mysqli $connection ресурс соединения
 * @param int $category id категории
 * @return int возвращает количество найденных лотов
 */
function get_count_all_lots(mysqli $connection, int $category): int
{
    $sql = "SELECT COUNT(*) FROM lots WHERE category_id = $category";
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }
    $count = mysqli_fetch_assoc($result);

    return (int) $count['COUNT(*)'];
}

/**
 * Функция добавления создания ставки лота
 * @param mysqli $connection ресурс соединения
 * @param int $user_id id пользователя
 * @param int $lot_id id лота
 * @param int $price сумма ставки
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
 * Возвращает сделанные ставки пользователя
 * @param mysqli $connection ресурс соединения
 * @param int $user id пользователя
 * @return array массив со ставками сделанные пользователем
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
 * Возвращает ставки лота
 * @param mysqli $connection ресурс соединения
 * @param int $lot id лота
 * @return array массив со ставками лота
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

/**
 * Возвращает последнюю ставку лота
 * @param mysqli $connection ресурс соединения
 * @param int $lot id лота
 * @return array|null в случае успеха, возвращает массив с последней ставкой
 */
function get_last_bet_of_lot(mysqli $connection, int $lot) : ?array
{
    $sql = "SELECT * FROM bets WHERE lot_id=? ORDER BY (dt_add) DESC LIMIT 1;";
    $data = [$lot];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result) ?? NULL;
}

/**
 * Возвращает массив с лотами, у которых не определен победитель
 * @param mysqli $connection ресурс соединения
 * @return array массив с лотами без победителей
 */
function get_lots_without_winner(mysqli $connection): array
{
    $sql = "SELECT * FROM lots WHERE dt_end < NOW() AND winner_id IS NULL";

    $result = mysqli_query($connection, $sql);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция добавляет победителя в лот
 * @param mysqli $connection ресурс соединения
 * @param int $lot id лота
 * @param int $winner_user id победившего пользователя
 */
function add_winner_to_lot(mysqli $connection,int $lot,int $winner_user) : void
{
    $sql = "UPDATE lots SET winner_id = ? WHERE id = ?";
    $data = [$winner_user, $lot];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }
}

/**
 * Возвращает массив с данными о пользователе по id
 * @param mysqli $connection ресурс соединения
 * @param int $user_id id пользователя
 * @return array возвращает массив с данными о пользователе
 */
function get_user_by_id(mysqli $connection, int $user_id) : array
{
    $sql = "SELECT * FROM users WHERE id=?";
    $data = [$user_id];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Возвращает имя пользователя по id
 * @param mysqli $connection ресурс соединения
 * @param int $user_id id пользователя
 * @return string возвращает имя пользователя
 */
function get_user_name_by_id(mysqli $connection, int $user_id) : string
{
    $sql = "SELECT name FROM users WHERE id='$user_id'";
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        exit('Error: ' . mysqli_error($connection));
    }
    $user = mysqli_fetch_assoc($result);

    return $user['name'];
}

/**
 * Возвращает имя катеогрии по id
 * @param mysqli $connection ресурс соединения
 * @param int $id id категории
 * @return string возвращает название категории
 */
function get_category_by_id(mysqli $connection, int $id): string
{
    $sql = "SELECT * FROM categories WHERE id=?";
    $data = [$id];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result)['name'];
}

/**
 * Возвращает массив с лотами из заданной категории
 * @param mysqli $connection ресурс соединения
 * @param int $category_id id категории
 * @param int $lots_per_page количество элементов на странице
 * @param int $current_page текущая страница
 * @return array возвращает массив с лотами категории
 */
function get_lots_by_category(mysqli $connection,int $category_id, int $lots_per_page, int $current_page) : array
{
    $offset = ($current_page - 1) * $lots_per_page;

    $sql = "SELECT l.id, l.name, l.user_id, l.winner_id, l.step, l.description, l.price, MAX(b.price) AS current_price, image,
                c.name AS category_name, l.dt_end
        FROM lots l
        JOIN categories c ON c.id = l.category_id
        LEFT JOIN bets b ON b.lot_id = l.id
        WHERE c.id = ?
        GROUP BY l.id
        LIMIT ? OFFSET ?";

    $data = [$category_id, $lots_per_page, $offset];
    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($connection));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция определяет победителя лота
 * @param mysqli $connection ресурс соединения
 * @param array $lot массив с лотом
 * @return int|null возвращает id победителя
 */
function complete_lot(mysqli $connection, array $lot): ?int
{
    $bets = get_last_bet_of_lot($connection, $lot['id']);
    if (!empty($bets)) {
        add_winner_to_lot($connection, $lot['id'], $bets['user_id']);
        return $bets['user_id'];
    }

    return null;
}
