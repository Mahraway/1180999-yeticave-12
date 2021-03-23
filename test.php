<?php

require_once __DIR__ . '/bootstrap.php';

echo $time = date('Y-m-d H:i:s', time()-(3600*30));
var_dump(get_correct_timer($time));
print '<br /><br /><br />';


var_dump(get_new_timer($time));
print '<br /><br /><br />';

print $over_date = $time;
