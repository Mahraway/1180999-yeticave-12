<?php

require 'bootstrap.php';

$str = 'categories';

$sql = "SELECT * FROM '" . $str . "'";

$result = mysqli_query($connection, $sql);

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

print_r($categories);
