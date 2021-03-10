<?php
/**
 * @var string $user
 * @var string $lot_url
 * @var string $lot_name
 * @var string $my_bets
 */
?>

<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= $user ?></p>
<p>Ваша ставка для лота <a href="<?= $lot_url ?>"><?= $lot_name ?></a> победила.</p>
<p>Перейдите по ссылке <a href="<?= $my_bets ?>">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
