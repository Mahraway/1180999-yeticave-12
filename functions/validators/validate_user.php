<?php

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
function validate_login_form(mysqli $connection, array $form_data): array
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
        if (!password_verify($password, $user['password'])) {
            return 'Вы ввели неверный пароль';
        }
    }

    return null;
}
