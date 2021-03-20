<?php
/**
 * @var string $message
 * @var mysqli $connection
 * @var int $category
 * @var array $lots
 * @var string $total_pages_count
 * @var int $count_total_founded_lots
 * @var int $current_page_number
 * @var int $lots_per_page
 */
?>

<section class="lots">
    <div class="lots__header">
        <h2>
            <?= $message . get_quote_for_string(get_category_by_id($connection, $category)) ?>
        </h2>
    </div>
    <ul class="lots__list">
        <?php foreach($lots as $lot): ?> <!--тут ругается на дулирование кода -->
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="/<?= $lot['image']?>" width="350" height="260" alt="<?= $lot['name']?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($lot['category_name']) ?></span>
                    <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= $lot['id'] ?>"><?= htmlspecialchars($lot['name'])?></a></h3>
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
                                    print $bets_count .  get_noun_plural_form(
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

<?php if ($count_total_founded_lots > $lots_per_page) : ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev ">
            <a class="<?= ($current_page_number === 1) ? 'page__item--hidden' : '' ?>"
               href="?page=<?= ($current_page_number - 1) ?>&category=<?= $category ?>">Назад
            </a>
        </li>

        <?php for ($i = 1;$i <= $total_pages_count; $i++) : ?>
            <li class="number pagination-item <?= $current_page_number === $i ? 'pagination-item-active' : '' ?>">
                <a href="?page=<?= $i ?>&category=<?= $category?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <li class="pagination-item pagination-item-next ">
            <a class="<?= ($current_page_number === $total_pages_count) ? 'page__item--hidden' : '' ?>"
               href="?page=<?= ($current_page_number + 1) ?>&category=<?= $category ?>">Вперед
            </a>
        </li>
    </ul>
<?php endif; ?>



