<?php
/**
 * Описание переменных
 * @var string $promo_menu шаблон блока promo_menu
 * @var string $lots_list шаблон с активными лотами
 * @var mysqli $connection ресурс соединения с БД
 *
 */
?>
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <?= $promo_menu ?>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?= $lots_list ?>
        </ul>
    </section>
