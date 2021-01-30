<?php

$user_date = $_POST['date'];
echo 'time now '.date('Y-m-d').'<br>';
echo 'custom date '.date('Y-m-d', strtotime($user_date));
if ((strtotime($_POST['date']) - time()) < 84600) {
    echo 'менее 1 дня';
}
?>
<form action="/test.php" method="post">
<input type="date" name="date">
    <input type="submit">
</form>
