<?php

/**
 * Функция обработки числовых параметров
 * @param string $id принимаеый параметр id
 * @return int|null в случае успешной проверки, возвращает целое число
 */
function get_param_id(string $id): ?int
{
    $id = $id ?? null;
    if (!$id || !is_numeric($id)) {
        return null;
    }
    return (int) $id;
}

/**
 * Функция возвращает введенное значение текстового поля формы
 * Ограничение: запросы методом POST
 * @param string $name название поля в форме добавления лота
 * @return string возвращает введенное значение поля формы
 */
function get_post_value(string $name) : string
{
    return $_POST[$name] ?? '';
}

/**
 * Функция возвращает введенное значение текстового поля формы
 * Ограничение: запросы методом GET
 * @param string $name название поля в форме добавления лота
 * @return string возвращает введенное значение поля формы
 */
function get_field_value(string $name) : string
{
    return $_GET[$name] ?? '';
}

/**
 * Возвращает номер текущей страницы
 * @param array $data
 * @return int если номера нет, возвращает номер страницы поумолчанию - 1
 */
function get_current_page_number(array $data) : int
{
    if (empty($data['page'])) {
        return 1;
    }
    return (int) $data['page'];
}

/**
 * @param int $count_total_founded_lots
 * @param int $lots_per_page
 * @return int
 */
function calculate_total_page_count(int $count_total_founded_lots, int $lots_per_page) : int
{
    return (int) ceil($count_total_founded_lots / $lots_per_page);
}

