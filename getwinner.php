<?php
/**
 * @var mysqli $connection
 * @var array $config
 */

require_once __DIR__ . '/bootstrap.php';

// находим лоты с датой до сегодня и без победителя
$lots = get_lots_without_winner($connection);

if (!empty($lots)) {
    foreach ($lots as $lot) {
        // находим ставки этих лотов
        $bets = get_last_bet_of_lot($connection, $lot['id']);
        if (!empty($bets)) {
            // записываем победителя в лот
            add_winner_to_lot($connection, $lot['id'], $bets['user_id']);
            // получаем данные победителя
            $user = get_user_by_id($connection, $bets['user_id']);

            // формируем письмо
            $message = include_template('email.php', [
                'user' => $user['name'],
                'lot_name' => $lot['name'],
                'lot_url' => $_SERVER["HTTP_HOST"] . '/lot.php?id=' . $lot['id'],
                'my_bets' => $_SERVER["HTTP_HOST"] . '/my-bets.php',
            ]);

            // отправляем письмо победителю
            winner_notice($config['mailer'], $user['email'], $message);
        }
    }
}









