<?php

require_once 'bootstrap.php';

$lots_per_page = $config['pagination']['lots_per_page'];

$search = '2015';
$email = 'rashid@mail.ru';

print_r(search_lots($connection,$search, $lots_per_page,1 ));

