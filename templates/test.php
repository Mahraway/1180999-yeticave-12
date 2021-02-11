<?php
//style

$key = '
    font-weight: 700;
    color: #45abde;
    font-size: 1.3em;
';

$val = '
    font-weight: 500;
    color: #444;
    font-size: 1.2em;
';

$main = '
    position: relative;
    margin: 40px auto;
    padding: 30px 0 20px 30px;
    max-width: 400px;
    box-shadow: 0 0  10px lightblue;
    border-radius: 10px;


';

?>
<div style="<?= $main; ?>"
    <p><span style="<?= $key?>">Имя пользователя:</span> <span style="<?= $val?>"><?= $user['name'] ?? 'нет данных'; ?></span></p>
    <p><span style="<?= $key?>">Электронная почта:</span> <span style="<?= $val?>"><?= $user['email'] ?? 'нет данных'; ?></span></p>
    <p><span style="<?= $key?>">Контакты для связи:</span> <span style="<?= $val?>"><?= $user['contacts'] ?? 'нет данных'; ?></span></p>
</div>
