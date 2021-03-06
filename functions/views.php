<?php

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template(string $name, array $data = []): string
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
        return ceil($price) . ' ₽';
    }

    return number_format($price, 0, ',', ' ') . ' ₽';
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
    $total = (strtotime($date) - time()) / MINUTE; // полных минут

    if ($total > 0) {
        $h = floor($total / MINUTE); // округление в меньшую сторону
        $m = $total % MINUTE; // остаток минут

        return [$h, $m];
    }

    return [0, 0];
}


/**
 * Функция возвращает SELECT для списка поля формы добавленя лота
 * @param array $form_data данные из формы лота
 * @param int $category_id id категории лота
 * @return string|null в случае успешной проверки, возвращает SELECT
 */
function get_post_select(array $form_data, int $category_id): ?string
{
    if (isset($form_data['category_id'])) {
        if ($category_id === $form_data['category_id']) {
            return 'selected';
        }
    }

    return null;
}

/**
 * Функция заключает текст в ковычки (текст => "текст")
 * @param string $text исходный текст
 * @return string текст, заключенный в ковычки
 */
function get_quote_for_string(string $text): string
{
    return $text ? $text = '«' . $text . '»' : '';
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
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
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
 * Функция форматирует время размещения ставки к читаемому формату" (5 минут назад, час назад и т. д.).
 * @param string $source_date_time - исходная дата
 * @param string $event_date_time - дата ставки
 * @return string возвращает преобразованную в читаемый вид дату ставки
 */
function format_time_after(string $source_date_time, string $event_date_time): string
{
    $timer = strtotime($source_date_time) - strtotime($event_date_time);

    if ($timer < 0) {
        return '';
    }

    if ($timer === 0) {
        return 'Только что';
    }
    if ($timer < MINUTE) {
        return $timer . get_noun_plural_form($timer,
                ' секунду назад',
                ' секунды назад',
                ' секунд назад'
            );
    }
    if ($timer < HOUR) {
        return floor($timer / MINUTE) . get_noun_plural_form(floor($timer / MINUTE),
                ' минуту назад',
                ' минуты назад',
                ' минут назад'
            );
    }
    if ($timer < DAY) {
        return floor($timer / HOUR) . get_noun_plural_form(floor($timer / HOUR),
                ' час назад',
                ' часа назад',
                ' часов назад'
            );
    }
    if ($timer >= DAY && $timer < DAY * 2) {
        return '1 день назад';
    }

    return date('y.m.d, в H:i', strtotime($event_date_time));
}

