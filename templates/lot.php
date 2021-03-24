<?php
/**
 * Описание переменных
 * @var array $lot массив с данными лота
 * @var array $bets массив со ставками лотов
 * @var array $categories массив с категориями
 * @var string $error код ошибки
 * @var mysqli $connection ресурс соединения с БД
 */
?>

    <section class="lot-item container">
        <h2><?= $lot['name']; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="../<?= $lot['image'] ?>" width="730" height="548" alt="<?= $lot['name'] ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['category_name'] ?></span></p>
                <p class="lot-item__description"><?= $lot['description'] ?></p>
            </div>
            <div class="lot-item__right">
                <?php if (isset($_SESSION['user'])
                    && $_SESSION['user']['id'] != $lot['user_id']) : ?>
                    <div class="lot-item__state">
                        <?php
                        $timer = get_time_before($lot['dt_end']);
                        $time_finishing_class = ($timer[0] < 1) ? 'timer--finishing' : '';
                        ?>
                        <div class="lot-item__timer timer <?= $time_finishing_class ?>">
                            <?php
                            echo $timer[0] . ":" . sprintf("%02d", $timer[1]);
                            ?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">

                                <?php
                                $last_bet = get_last_bet_of_lot($connection, $lot['id']);
                                if (!$last_bet) : ?>
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= format_price($lot['price']); ?></span>
                                <?php else : ?>
                                    <span class="lot__amount">Текущая цена</span>
                                    <span class="lot__cost"><?= format_price($lot['price'] = $last_bet['price']) ?></span>
                                <?php endif; ?>

                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка: <span><?= format_price($lot['price'] + $lot['step']) ?></span>
                            </div>
                        </div>
                        <form class="lot-item__form" action="/lot.php?id=<?= $lot['id'] ?>" method="post"
                              autocomplete="off">
                            <p class="lot-item__form-item form__item form__item<?= $error ? '--invalid' : null ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" placeholder="<?= $lot['price'] + $lot['step'] ?>"
                                       value="<?= $form_data['cost'] ?? '' ?>">
                                <span class="form__error"><?= $error ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <?php endif; ?>
                <div class="history">
                    <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bets as $bet) : ?>
                            <tr class="history__item">
                                <td class="history__name"><?= get_user_name_by_id($connection, $bet['user_id']) ?></td>
                                <td class="history__price"><?= format_price($bet['price']) ?></td>
                                <td class="history__time"><?= format_time_after($bet['dt_add']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
