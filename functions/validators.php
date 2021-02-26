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
 * Функция проверки проверки полей формы регистрации
 * @param mysqli $connection идентификатор соединения с базой данных
 * @param array $form_data данные из формы регистрации
 * @return array возвращает массив с кодами ошибок
 */
function validate_registration_form(mysqli $connection, array $form_data): array
{
    $errors = [];
    $required = ['email', 'password', 'name', 'contacts'];

    $errors['email'] = validate_registration_email($connection, $form_data['email']);
    $errors['password'] = validate_registration_password($form_data['password']);
    $errors['name'] = validate_registration_name($form_data['name']);
    $errors['contacts'] = validate_registration_contacts($form_data['contacts']);

    foreach ($required as $val) {
        if ($errors[$val]) {
            return $errors;
        }
        unset($errors[$val]);
    }

    return $errors;
}

/**
 * Проверяет поле email, а так же на уникальность введенного email
 * @param mysqli $connection идентификатор соединения
 * @param string $email данные из поля email
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_registration_email(mysqli $connection, string $email): ?string
{
    if (empty($email)) {
        return 'Введите ваш email';
    }

    if (strlen($email) > 255) {
        return 'Введите не более 255 символов';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Введите существующий email';
    }

    $user = get_user_by_email($connection, $email);
    if ($user) {
            return 'Пользователь с таким email уже существует';
    }

    return null;
}


/**
 * Функция проверки пароля
 * @param string $password данные из поля пароль
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_registration_password(string $password): ?string
{
    if (empty($password)) {
        return 'Введите пароль';
    }
    if (strlen($password) > 255) {
        return 'Введите не более 255 символов';
    }

    return null;
}

/**
 * Функция проверки поля имя
 * @param string $name данные из поля имя
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_registration_name(string $name): ?string
{
    if (empty($name)) {
        return 'Введите ваше имя';
    }

    if (strlen($name) > 255) {
        return 'Введите не более 255 символов';
    }

    return null;
}

/**
 * Функция проверки поля контакты
 * @param string $contacts данные из поля контакты
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_registration_contacts(string $contacts): ?string
{
    if (empty($contacts)) {
        return 'Укажите контакты для связи';
    }

    if (strlen($contacts) > 255) {
        return 'Введите не более 255 символов';
    }

    return null;
}

/**
 * Функция проверки формы авторизации
 * @param mysqli $connection идентификатор соединения
 * @param array $form_data данные из формы
 * @return array возващает массив с кодами ошибок
 */
function validate_login_form(mysqli $connection, array $form_data) : array
{
    $errors = [];
    $required = ['email', 'password'];

    $errors['email'] = validate_login_email($connection, $form_data['email']);
    $errors['password'] = validate_login_password($connection, $form_data['email'], $form_data['password']);

    foreach ($required as $val) {
        if ($errors[$val]) {
            return $errors;
        }
        unset($errors[$val]);
    }

    return $errors;
}

/**
 * Проверяет поле email, а так же на уникальность введенного email
 * @param mysqli $connection идентификатор соединения
 * @param string $email данные из поля email
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_login_email(mysqli $connection, string $email): ?string
{
    if (empty($email)) {
        return 'Введите ваш email';
    }

    if (strlen($email) > 255) {
        return 'Введите не более 255 символов';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Введите существующий email';
    }

    $user = get_user_by_email($connection, $email);
    if (!$user) {
        return 'Пользователь не найден';
    }

    return null;
}

/**
 * Функция проверки пароля
 * @param mysqli $connection идентификатор соединения
 * @param string $email указанный e-mail
 * @param string $password указанный пароль
 * @return string|null возващает массив с кодами ошибок, если они есть
 */
function validate_login_password(mysqli $connection, string $email, string $password): ?string
{
    if (empty($password)) {
        return 'Введите пароль';
    }
    if (strlen($password) > 255) {
        return 'Введите не более 255 символов';
    }

    $user = get_user_by_email($connection, $email);
    if ($user) {
        if (!password_verify($password, $user['pass'])) {
            return 'Вы ввели неверный пароль';
        }
    }
    return null;
}

/**
 * Функция проверки полей формы добавления лота
 * В ходе проверки записывает ошибки, если ошибок нет, то возвращает пустой массив.
 * @param array $form_data данные из формы
 * @param array $files массив с файлами
 * @return array возващает массив с кодами ошибок
 */
function validate_lot_form(array $form_data, array $files): array
{
    $errors = [];
    $required = ['name','description', 'price', 'step', 'dt_end', 'category_id', 'image'];

    $errors['name'] = validate_lot_name($form_data['name']);
    $errors['description'] = validate_lot_description($form_data['description']);
    $errors['price'] = validate_lot_price($form_data['price']);
    $errors['step'] = validate_lot_step($form_data['step']);
    $errors['dt_end'] = validate_lot_date($form_data['dt_end']);
    $errors['category_id'] = validate_lot_category($form_data['category_id']);
    $errors['image'] = validate_lot_file($files);

    foreach ($required as $val) {
        if ($errors[$val]) {
            return $errors;
        }
        unset($errors[$val]);
    }

    return $errors;
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


/** Функция проверки загружаемого файла
 * @param array $file данные добавленного файла
 * @return string|null возвращает код ошибки, если он есть
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
            return null;
        }
        return $err[1];
    }
    return $err[0];
}


function validate_search_form(string $search) : ?string
{
    if (!$search) {
        return 'Пустой запрос';
    }
    if (strlen($search) > 255) {
        return  'Длинный запрос';
    }
    return null;
}
