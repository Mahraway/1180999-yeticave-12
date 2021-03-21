<?php

/**
 * Функция подсчета общего количества в пагинации
 * @param int $count_total_founded_lots
 * @param int $lots_per_page
 * @return int возвращает общее количество страницы в пагинации
 */
function calculate_total_page_count(int $count_total_founded_lots, int $lots_per_page): int
{
    return (int)ceil($count_total_founded_lots / $lots_per_page);
}
