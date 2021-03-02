<?php
/**
 * @var string $promo_menu шаблон блока promo_menu
 * @var array $lots массив с активными лотами
 * @var mysqli $connection
 *
 */
?>
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <?= $promo_menu; ?>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php foreach($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $lot['image']?>" width="350" height="260" alt="<?= $lot['name']?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($lot['category_name']) ?></span>
                    <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= $lot['id'] ?>"><?= htmlspecialchars($lot['name'])?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <?php
                            $current_price = get_last_bet_of_lot($connection, $lot['id'])['price'];
                            $bets_count = count(get_bets_by_lot($connection,$lot['id']));
                            if (!$current_price): ?>
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= format_price($lot['price']); ?></span>
                            <?php else: ?>
                                <span class="lot__amount">
                                    <?= $bets_count . get_noun_plural_form(
                                        $bets_count,
                                        ' ставка',
                                        ' ставки',
                                        ' cтавок'
                                    ) ?>
                                </span>
                                <span class="lot__cost"><?= format_price($current_price) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php
                        $timer = get_time_before($lot['dt_end']);
                        $time_finishing_class = ($timer[0] < 1) ? 'timer--finishing':  '';
                        ?>
                        <div class="lot__timer timer <?= $time_finishing_class; ?>">
                        <?= $timer[0].":".sprintf("%02d", $timer[1]); ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
