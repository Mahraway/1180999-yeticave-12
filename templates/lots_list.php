<?php
/**
 * Описание переменных
 * @var array $lots массив с лотами
 * @var mysqli $connection ресурс соединения с БД
 */
?>

<?php foreach ($lots as $lot): ?>
    <li class="lots__item lot">
        <div class="lot__image">
            <a href="/lot.php?id=<?= $lot['id'] ?>"><img src="/<?= $lot['image'] ?>" width="350" height="260"
                                                         alt="<?= $lot['name'] ?>"></a>
        </div>
        <div class="lot__info">
            <span class="lot__category"><?= htmlspecialchars($lot['category_name']) ?></span>
            <h3 class="lot__title"><a class="text-link"
                                      href="/lot.php?id=<?= $lot['id'] ?>"><?= htmlspecialchars($lot['name']) ?></a>
            </h3>
            <div class="lot__state">
                <div class="lot__rate">

                    <?php
                    $last_bet = get_last_bet_of_lot($connection, $lot['id']);
                    if (!$last_bet) : ?>
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?= format_price($lot['price']); ?></span>
                    <?php else : ?>
                        <span class="lot__amount">
                                    <?php
                                    $bets_count = count(get_bets_by_lot($connection, $lot['id']));
                                    print $bets_count . get_noun_plural_form(
                                            $bets_count,
                                            ' ставка',
                                            ' ставки',
                                            ' cтавок');
                                    ?>
                                </span>
                        <span class="lot__cost"><?= format_price($last_bet['price']) ?></span>
                    <?php endif; ?>
                </div>
                <?php
                $timer = get_time_before($lot['dt_end']);
                $time_finishing_class = ($timer[0] < 1) ? 'timer--finishing' : '';
                ?>
                <div class="lot__timer timer <?= $time_finishing_class; ?>">
                    <?= $timer[0] . ":" . sprintf("%02d", $timer[1]); ?>
                </div>
            </div>
        </div>
    </li>
<?php endforeach; ?>
