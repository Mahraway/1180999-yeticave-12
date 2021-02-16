<?php

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template(string $name, array $data = []) : string
{
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
        }
    }
    return null;
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

