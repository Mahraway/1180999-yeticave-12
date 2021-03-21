<?php

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
    $required = ['name', 'description', 'price', 'step', 'dt_end', 'category_id', 'image'];

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
 * @param $price int данные из формы поля начальная цена
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_lot_price(int $price): ?string
{
    if ($price <= 0) {
        return 'Введите начальную цену';
    }
    $_POST['price'] = $price;
    return null;
}

/**
 * Функиця проверки поля шага ставки
 * Проверка заполненности и условие, что шаг ставки целое число большее нуля
 * @param int $step данные из формы поля шаг ставки
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_lot_step(int $step): ?string
{
    if ($step <= 0) {
        return 'Введите шаг ставки';
    }
    $_POST['step'] = $step;

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

/**
 * Функция проверки загружаемого файла
 * @param array $file данные добавленного файла
 * @return string|null возвращает код ошибки, если он есть
 */
function validate_lot_file(array $file): ?string
{
    $file_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $file_temp = $file['image']['tmp_name'];

    if (is_uploaded_file($file_temp)) {
        $file_type = mime_content_type($file_temp);
        if (in_array($file_type, $file_types)) {
            return null;
        }

        return 'Неверный тип файла. Добавьте изображение в формате jpg или png';
    }

    return 'Добавьте изображение';
}
