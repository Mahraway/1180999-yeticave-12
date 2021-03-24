<?php

require_once __DIR__ . '/bootstrap.php';

$current_time = time();
$current_date = date('Y-m-d H:i:s', $current_time);
$event_date = date('Y-m-d H:i:s', $current_time-3600*47);
var_dump(format_time_after($current_date, $event_date));
