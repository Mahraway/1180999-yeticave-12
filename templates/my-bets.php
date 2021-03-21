<?php
/**
 * Описание переменных
 * @var array $bets массив со ставками
 * @var array $categories массив с категориями
 * @var mysqli $connection ресурс соединения с БД
 */
?>

    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">

            <?php foreach ($bets as $bet) :
                $lot = get_lot($connection, $bet['lot_id']);
                $item_class = '';
                if ($lot['winner_id'] == $_SESSION['user']['id']) {
                    $item_class = '--win';
                } elseif (strtotime($lot['dt_end']) < time()) {
                    $item_class = '--end';
                }
                ?>
                <tr class="rates__item rates__item<?= $item_class ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= $lot['image'] ?>" width="54" height="40" alt="<?= $lot['name'] ?>>">
                        </div>
                        <div>
                            <h3 class="rates__title"><a href="lot.php?id=<?= $lot['id'] ?>"><?= $lot['name'] ?></a></h3>
                            <?php if ($item_class === '--win'): ?>
                                <p><b>Контакты автора:</b> <?= get_user_by_id($connection, $lot['user_id'])['contacts'] ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="rates__category">
                        <?= $lot['category_name'] ?>
                    </td>
                    <td class="rates__timer">
                        <?php
                        $timer = get_time_before($lot['dt_end']);
                        $time_finishing_class = ($timer[0] < 1) ? ' timer--finishing' : '';
                        if (strtotime($lot['dt_end']) < time()) {
                            $time_finishing_class = '--end';
                        }
                        if ($item_class === '--win') {
                            $time_finishing_class = $item_class;
                            $timer_class = 'Ставка выиграла';
                        } elseif ($item_class === '--end') {
                            $timer_class = 'Торги окончены';
                        } else {
                            $timer_class = $timer[0] . ":" . sprintf("%02d", $timer[1]);
                        }
                        ?>
                        <div class="timer timer<?= $time_finishing_class ?? '' ?>">
                            <?= $timer_class ?>
                        </div>
                    </td>
                    <td class="rates__price">
                        <?= format_price(get_last_bet_of_lot($connection, $lot['id'])['price']) ?>
                    </td>
                    <td class="rates__time">
                        <?= get_correct_timer($bet['dt_add']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
