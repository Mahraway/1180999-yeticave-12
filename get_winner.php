<?php
/**
 * Описание переменных
 * @var mysqli $connection ресурс соединения БД
 * @var array $config массив с кофнигурацией сайта
 */

require_once __DIR__ . '/bootstrap.php';

$lots = get_lots_without_winner($connection);

if (!empty($lots)) {
    foreach ($lots as $lot) {
        $winner_id = complete_lot($connection, $lot);
        if ($winner_id) {
            $winner = get_user_by_id($connection, $winner_id);
            $message = include_template('email.php', [
                'user' => $winner['name'],
                'lot_name' => $lot['name'],
                'lot_url' => $_SERVER["HTTP_HOST"] . '/lot.php?id=' . $lot['id'],
                'my_bets' => $_SERVER["HTTP_HOST"] . '/my-bets.php',
            ]);
            notify_winner($config['mailer'], $winner['email'], $message);
        }
    }
}







