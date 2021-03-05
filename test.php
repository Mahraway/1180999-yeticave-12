<?php

require_once 'bootstrap.php';

print get_last_bet_of_lot($connection, 1)['user_id'];
