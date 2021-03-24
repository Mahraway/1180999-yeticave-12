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

    return (int)$id;
}

/**
 * Возвращает номер текущей страницы
 * @param array $data
 * @return int если номера нет, возвращает номер страницы поумолчанию - 1
 */
function get_current_page_number(array $data): int
{
    if (empty($data['page'])) {
        return 1;
    }

    return (int)$data['page'];
}


