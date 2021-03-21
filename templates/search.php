<?php
/**
 * Описание переменных
 * @var array $lots массив с лотами
 * @var array $categories массив с категориями
 * @var string $search данные с формы поиска
 * @var string $message сообщение с результатом поиска
 * @var string $total_pages_count количество страницы для пагинации
 * @var int $count_total_founded_lots количество лотов для пагинации
 * @var int $current_page_number номер текущей страницы
 * @var int $lots_per_page количество лотов на страницу
 */
?>

    <section class="lots">
        <h2><span><?= $message ?> <?= get_quote_for_string($search) ?> </span></h2>
        <ul class="lots__list">
            <?php foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="../<?= $lot['image'] ?>" width="350" height="260" alt="<?= $lot['name'] ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $lot['category_name'] ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="../lot.php/?id=<?= $lot['id'] ?>"><?= $lot['name'] ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= format_price($lot['price']) ?></span>
                            </div>
                            <div class="lot__timer">
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
        </ul>
    </section>

<?php if ($count_total_founded_lots > $lots_per_page) : ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev ">
            <a class="<?= ($current_page_number === 1) ? 'page__item--hidden' : '' ?>"
               href="?page=<?= ($current_page_number - 1) ?>&search=<?= $search ?>">Назад
            </a>
        </li>

        <?php for ($i = 1; $i <= $total_pages_count; $i++) : ?>
            <li class="number pagination-item <?= $current_page_number === $i ? 'pagination-item-active' : '' ?>">
                <a href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <li class="pagination-item pagination-item-next ">
            <a class="<?= ($current_page_number === $total_pages_count) ? 'page__item--hidden' : '' ?>"
               href="?page=<?= ($current_page_number + 1) ?>&search=<?= $search ?>">Вперед
            </a>
        </li>
    </ul>
<?php endif; ?>
