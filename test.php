<?php
/**
 * @var mysqli $connection
 */
require_once 'bootstrap.php';

$user_id = 3;
$bets = get_my_bets($connection, $user_id);
$lot = get_lot($connection, $bets[0]['lot_id']);
print_r($lot);


