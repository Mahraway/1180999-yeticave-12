<?php


print_r($_POST);

$arr1 = array_map(function($var){
    return htmlspecialchars($var, ENT_QUOTES);
}, $_POST);

$arr2 = array_map(function ($var){
    return strip_tags($var);
}, $arr1);


print '<br>';
print_r($arr1);
print '<br>';
print_r($arr2)
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="/test.php" method="post">
    <input type="text" name="test">
</form>
</body>
</html>
