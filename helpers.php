<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
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
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция для форматирования суммы и добавления к ней знака рубля
 * Ограничения: Функция должна принимать один аргумент — число.
 * @param int $price Число
 * @return string Форматированая строка вывода стоимости в карточке товара
 */

function format_price(int $price): string
{
    if ($price < 1000) {
        return ceil($price).' ₽';
    }
    return number_format($price, 0, ',', ' ').' ₽';
}

/**
* Возвращает количество целых часов и остатка минут до даты из будущего
* Ограничения: функция принимает один аргумент - дату в формате ГГГГ-ММ-ДД;
* @param string $date Строка
* @return array Массив, где первый элемент — целое количество часов до даты,
*                                              а второй — остаток в минутах;
*/

function get_time_before(string $date): array
{
    $total = (strtotime($date) - time())/60; // полных минут

    if ($total > 0) {
        $h = floor($total/60); // округление в меньшую сторону
        $m = $total % 60; // остаток минут

        return [$h, $m];
    }
    return [0, 0];
}

/**
 * Функция проверки подлкючения К БД
 * Ограничения: функция принимает четыре аргумента - хост, имя пользователя, пароль и имя БД
 * @param string $host обычно localhost
 * @param string $user имя пользователя
 * @param string $pass пароль
 * @param string $db имя базы данных
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
 * Описание: функуия формирования названия категории лота
 * Алгоритм: ID категории лота сравнивает с массивом категорий
 * При выполнении условия, возвращает название категории
 * @param array $lot массив с данными лота
 * @param array $categories массив с категориями
 * @return string $name возвращает возвращает название категории лота
 */
function get_category_name(array $lot, array $categories): string
{
    $result = $lot['category_id'];
    foreach($categories as $category) {
        switch ($result) {
            case $category['id']:
                $category_name = $category['name'];
        break;
        }
    }
    return $category_name;
}

/**
 * Принимает глабоальный массив $_GET.
 * Проверяет на сущестование элемента id массива
 * @param array $param глобальный массив $_GET
 * @return (int or null) в случае успешной проверки возвращает целое число, иначе null
 */
function getParamId(array $param): ?int
{
    $id = $param['id'] ?? null;
    if (!$id || !is_numeric($id)) {
        return null;
    }
    return (int) $id;
}

/**
 * Функция сохраняет введенное значение в текстовом поле
 * @param string $name
 * @return string
 */
function get_post_value(string $name) : string
{
    return $_POST[$name] ?? '';
}

/**
 * Функция сохраняет выбранное значение в выпадающем списке SELECT
 * @param string $name
 * @return string
 */
function get_post_select(string $name) : ?string
{
    if (isset($_POST['category'])) {
        if ($name == $_POST['category']) {
            return 'selected';
        }
    }
    return null;
}


/** Функция проверки полей формы лота, найденные ошибки записывает в массив
 * Если ошибок нет, возвращает пустой массив
 * @return array $error массив ошибок
 */
function validateLotForm(): array
{
    $errors = [];

    // Обязаьельные поля
    $required_fields = ['lot-name', 'message', 'lot-rate', 'lot-step', 'lot-date','category'];

    // Числовые поля
    $numeric_fields = ['lot-rate', 'lot-step','category'];

    foreach ($required_fields as $field) {
        $error = validateFilled($field);
        if ($error) {
            $errors[$field] = $error;
        }
        $error = validateLength($field);
        if ($error) {
            $errors[$field] = $error;
        }
    }

    foreach ($numeric_fields as $field) {
        $_POST['lot-step'] = str_replace(',','.', $_POST['lot-step']);
        $error = validateNumeric($field);
        if ($error) {
            $errors[$field] = $error;
        }
    }

    if (is_date_valid($_POST['lot-date'])) {
        $user_date = strtotime($_POST['lot-date']);
        if ($user_date - time() < 60*60*12) {
            $errors['lot-date'] = 'Дата должна быть больше 1 день';
        }
    }

    if (!is_null(validateFile())) {
        $errors['lot-img'] = validateFile();
    }

    if ($_POST['lot-rate'] <= 0) {
        $errors['lot-rate'] = 'Введите число больше нуля';
    }

    if (is_numeric($_POST['lot-step']) && $_POST['lot-step'] > 0) {
        $_POST['lot-step'] = round($_POST['lot-step']);
    } else {
        $errors['lot-step'] = 'Введите положительное число больше нуля';
    }

    return $errors;
}


// Ниже функции проверки полей формы


/** Проверка на заполненность поля
 * @param string $field ключ в массиве $_POST, поле формы
 * @return string|null если элемент не существует, записывает код ошибки в массив ошибок
 */
function validateFilled(string $field): ?string
{
    if (empty($_POST[$field])) {
        return 'Поле должно быть заполнено';
    }
    return null;
}

/** Проверка число ли введено
 * @param string $field данные с поля формы
 * @return string|null если введно не число, то записывает код ошибки в массив ошибок
 */
function validateNumeric(string $field): ?string
{
    if (!is_numeric($_POST[$field])) {
        return 'Введите числовое значение';
    }
    return null;
}

/** Проевряет длинну строки, в БД ограничение до 255 символов
 * @param string $field данные с поля формы
 * @return string|null если введенная строка больше 255, записывает код ошибки в массив ошибок
 */
function validateLength(string $field): ?string
{
    if (strlen($_POST[$field]) > 255 ) {
        return 'Не более 255 символов';
    }
    return null;
}

/** Функция проверки добавляемого файла
 * @return string|null если проверка прошла успешно, то размещает в корневой папке uploads,
 *                     в случае ошибки записывает код ошибки в массив ошибок
 */
function validateFile(): ?string
{
    $err = [
        'Добавьте изображение в формате jpg или png',
        'Неверный тип файла. Добавьте изображение в формате jpg или png'
    ];
    $file_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $file_temp = $_FILES['lot-img']['tmp_name'];
    $file_name = $_FILES['lot-img']['name'];

    if (is_uploaded_file($file_temp)) {
        $file_type = mime_content_type($file_temp);
        if (in_array($file_type, $file_types)) {
            $file_path = __DIR__ . '/uploads/';
            move_uploaded_file($file_temp, $file_path . $file_name);
            $_FILES['lot-img']['img-url'] = 'uploads/' . $file_name;
            return null;
        }
        return $err[1];
    }
    return $err[0];
}
