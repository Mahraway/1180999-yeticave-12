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
 * Описание: функуия формирования названия категории лота
 * Алгоритм: ID категории лота сравнивает с массивом категорий
 * При выполнении условия, возвращает название категории
 * @param array $lot массив с данными лота
 * @param array $categories массив с категориями
 * @return string $name возвращает возвращает название категории лота
 */
function get_category_name(array $lot, array $categories): ?string
{
    $result = $lot['category_id'];
    foreach($categories as $category) {
        switch ($result) {
            case $category['id']:
                return $category['name'];
        break;
        }
    }
    return null;
}

/**
 * Проверяет возвращает значение параметра id из $_GET
 * @return int|null в случае успешной проверки возвращает целое число
 */
function get_param_id(): ?int
{
    $id = $_GET['id'] ?? null;
    if (!$id || !is_numeric($id)) {
        return null;
    }
    return (int) $id;
}

/**
 * Функция возвращает введенное значение текстового поля формы
 * @param string $name название поля в форме добавления лота
 * @return string возвращает введенное значение поля формы
 */
function get_post_value(string $name) : string
{
    return $_POST[$name] ?? '';
}

/**
 * Функция возвращает SELECT для списка поля формы добавленя лота
 * @param string $category_id id категории лота
 * @return string|null в случае успешной проверки, возвращает SELECT
 */
function get_post_select(string $category_id) : ?string
{
    if (isset($_POST['category_id'])) {
        if ($category_id == $_POST['category_id']) {
            return 'selected';
        }
    }
    return null;
}

/**
 * Функция загружает файл в папку 'uploads/' и записывает путь к файлу в суперглобальную переменную массива $_FILES
 * @param array $file массив с данными о файле
 */
function upload_file(array $file)
{
    if (!empty($file['image']['name'])) {
        $file_name = $file['image']['name'];
        $file_temp = $file['image']['tmp_name'];
        $file_path = __DIR__ . '/uploads/';
        $file_status = move_uploaded_file($file_temp, $file_path . $file_name);

        if ($file_status == true) {
            $_FILES['image']['url'] = 'uploads/' . $file_name;
        } else {
            exit('При загрузке файла, произошла критическая ошибка');
        }
    }
}

/**
 * Функция проверки полей формы
 * В ходе проверки записывает ошибки, если ошибок нет, то возвращает пустой массив.
 * @param array $formData данные из формы
 * @return array $errors массив с кодами ошибок
 */
function validate_lot_form(array $formData): array
{
    $errors = [];
    $required = ['name','description', 'price', 'step', 'dt_end', 'category_id', 'image'];

    $errors['name'] = validate_lot_name($formData['name']);
    $errors['description'] = validate_lot_description($formData['description']);
    $errors['price'] = validate_lot_price($formData['price']);
    $errors['step'] = validate_lot_step($formData['step']);
    $errors['dt_end'] = validate_lot_date($formData['dt_end']);
    $errors['category_id'] = validate_lot_category($formData['category_id']);
    $errors['image'] = validate_lot_file($_FILES);

    for ($i = 0; $i < count($required); $i++) {
        if ($errors[$required[$i]]) {
            return $errors;
        }
    }
    return $errors = [];
}

/**
 * Функция проверки имени лота
 * Проверяет заполнение и длину
 * @param string $name данные из поля наименования формы
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_lot_name(string $name): ?string
{
    if (empty($name)) {
        return 'Введите название лота';
    }
    if (strlen($name) > 255) {
        return 'Введите не более 255 символов';
    }
    return null;
}

/**
 * Функция проверки категории лота
 * @param string $id идентификатор картегрии лота
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_lot_category(string $id): ?string
{
    if (empty($id)) {
        return 'Выберите категорию';
    }
    return null;
}

/**
 * Функция проверки описания лота
 * Проверяет заполненность и лимит строки до 1000 символов
 * @param string $description данные из поля описания  лота
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_lot_description(string $description): ?string
{
    if (empty($description)) {
        return 'Напишите описание лота';
    }
    if (strlen($description) > 1000) {
        return 'Введите не более 1000 символов';
    }
    return null;
}

/**
 * Функиця проверки поля с ценой
 * Проверерка заполненности и на положительное число
 * @param $price string данные из формы поля начальная цена
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_lot_price(string $price): ?string
{
    if (empty($price)) {
        return 'Введите начальную цену';
    }
    if ($price <= 0 || !is_numeric($price)) {
        return 'Введите число больше нуля';
    }
    return null;
}

/**
 * Функиця проверки поля шага ставки
 * Проверка заполненности и условие, что шаг ставки целое число большее нуля
 * @param string $step данные из формы поля шаг ставки
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_lot_step(string $step): ?string
{
    if (empty($step)) {
        return 'Введите шаг ставки';
    }

    if (is_numeric($step) && $step > 0) {
        $_POST['step'] = round($step, 0);
    } else {
        return 'Введите целое число большее нуля';
    }
    return null;
}

/**
 * Функиця проверки даты окончания торгов
 * Проверка заполненности и условия, в котором дата завершения торгов должна быть больше 1 дня
 * @param string $date данные из формы поля дата
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_lot_date(string $date): ?string
{
    if (empty($date)) {
        return 'Введите дату завершения торгов';
    }

    if (is_date_valid($date)) {
        if ((strtotime($date) - time()) < 43200) {
            return 'Дата должна быть больше одного дня';
        }
    }
    return null;
}


/** Функция проверки добавляемого файла
 * @param array $file данные добавленного файла
 * @return string|null если проверка прошла успешно, то размещает в корневой папке uploads,
 *                     иначе возвращает код ошибки
 */
function validate_lot_file(array $file): ?string
{
    $err = [
        'Добавьте изображение',
        'Неверный тип файла. Добавьте изображение в формате jpg или png'
    ];
    $file_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $file_temp = $file['image']['tmp_name'];

    if (is_uploaded_file($file_temp)) {
        $file_type = mime_content_type($file_temp);
        if (in_array($file_type, $file_types)) {
            upload_file($file);
            return null;
        }
        return $err[1];
    }
    return $err[0];
}

