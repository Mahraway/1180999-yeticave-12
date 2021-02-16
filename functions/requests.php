<?php

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

