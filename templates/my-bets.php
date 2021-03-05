<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($bets as $bet) :
            $lot = get_lot($connection, $bet['lot_id']);
            ?>
        <tr class="rates__item rates__item"> <!-- статус ставки "--end(--win)"-->

            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?= $lot['image'] ?>" width="54" height="40" alt="Сноуборд">
                </div>
                <h3 class="rates__title"><a href="lot.php?id=<?= $lot['id'] ?>"><?= $lot['name'] ?></a></h3>
            </td>
            <td class="rates__category">
                <?= get_category_name($lot, $categories)?>
            </td>
            <td class="rates__timer">
                <?php
                $timer = get_time_before($lot['dt_end']);
                $time_finishing_class = ($timer[0] < 1) ? ' timer--finishing':  '';
                ?>
                <div class="timer timer<?= $time_finishing_class?>">
                    <?php
                    echo $timer[0].":".sprintf("%02d", $timer[1]);
                    ?>
                </div>
            </td>
            <td class="rates__price">
                <?= format_price($lot['price']) ?>
            </td>
            <td class="rates__time">
                <?= date('y.m.d в H:i', strtotime($bet['dt_add'])) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>
