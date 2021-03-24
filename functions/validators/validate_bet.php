<?php

/**
 * Функция проверки формы добавления ставки лота
 * @param mysqli $connection ресурс соединения
 * @param array $form_data данные из формы лота
 * @param array $lot масств с данными лота
 * @return string|null возвращает текст ошибки или null если валидация прошла успешно
 */
function validate_add_bet(mysqli $connection, array $form_data, array $lot): ?string
{
    $errors = validate_bet_field($connection, $form_data['cost'], $lot);

    return $errors ?? null;
}

/**
 * Функция проверки поля формы добавления ставки
 * @param mysqli $connection ресурс соединения
 * @param string $bet данные из формы добавления ставки
 * @param array $lot массив с данными о лоте
 * @return string|null возвращает текст ошибки или null если валидация прошла успешно
 */
function validate_bet_field(mysqli $connection, string $bet, array $lot): ?string
{
    if (!$bet) {
        return 'Введите вашу ставку';
    }

    if (!is_numeric($bet)) {
        return 'Ставка должна быть числом';
    }

    if (strtotime($lot['dt_end']) - time() < 0) {
        return 'Срок размещения лота истек';
    }

    $last_bet = get_last_bet_of_lot($connection, $lot['id']);
    if (!empty($last_bet)) {

        if ($bet < $last_bet['price'] + $lot['step']) {
            return 'Повысьте ставку';
        }

        if ($last_bet['user_id'] === $_SESSION['user']['id']) {
            return 'Последняя ставка сделана текущим пользователем';
        }
    } else {
        if ($bet < $lot['price'] + $lot['step']) {
            return 'Повысьте ставку';
        }
    }

    return null;
}
