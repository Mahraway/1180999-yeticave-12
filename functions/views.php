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
    $total = (strtotime($date) - time()) / 60; // полных минут

    if ($total > 0) {
        $h = floor($total / 60); // округление в меньшую сторону
        $m = $total % 60; // остаток минут

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
 * Функция преобразует время размещения ставки в "человеческий формат" (5 минут назад, час назад и т. д.).
 * @param string $date дата размещения ставки
 * @return string возвращает корректный формат времени ставки
 */
function get_correct_timer(string $date): string
{
    $correct_timer = '';
    $timer = (time() - strtotime($date));
    if ($timer > 0) {
        switch ($timer) {
            case ($timer <= 60):
                $correct_timer = $timer . get_noun_plural_form($timer,
                        ' секунду назад',
                        ' секунды назад',
                        ' секунд назад'
                    );
                break;
            case ($timer <= 3600):
                $correct_timer = round($timer / 60) . get_noun_plural_form(round($timer / 60),
                        ' минуту назад',
                        ' минуты назад',
                        ' минут назад'
                    );
                break;
            case ($timer <= 3600 * 60 && $timer <= 86400):
                $correct_timer = round($timer / 3600) . get_noun_plural_form(round($timer / 3600),
                        ' час назад',
                        ' часа назад',
                        ' часов назад'
                    );
                break;
            case ($timer > 86400 && $timer < 86400 * 2):
                $correct_timer = 'Вчера, в ' . date('H:i', strtotime($date));
                break;
            case ($timer > 86400 * 2):
                $correct_timer = date('d.m.y в H:i', strtotime($date));
        }
    }

    return $correct_timer;
}
