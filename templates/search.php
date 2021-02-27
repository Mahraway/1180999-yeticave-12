<?php
/**
 * @var array $lots
 * @var array $categories
 * @var string $search
 * @var string $message
 * @var string $total_pages_count
 * @var int $count_total_founded_lots
 * @var int $current_page_number
 */
?>

<section class="lots">
    <h2><span><?= $message ?> <?= get_quote_for_string($search) ?> </span></h2>
    <ul class="lots__list">
        <?php foreach ($lots as $lot) : ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="/<?= $lot['image'] ?>" width="350" height="260" alt="<?= $lot['name'] ?>">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?= get_category_name($lot,$categories)?></span>
                <h3 class="lot__title"><a class="text-link" href="../lot.php/?id=<?= $lot['id'] ?>"><?= $lot['name'] ?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?= format_price($lot['price'])?></span>
                    </div>
                    <div class="lot__timer">
                        <?php
                        $timer = get_time_before($lot['dt_end']);
                        $time_finishing_class = ($timer[0] < 1) ? 'timer--finishing':  '';
                        ?>
                        <!-- Вывод таймера лота -->
                        <div class="lot__timer timer <?= $time_finishing_class; ?>">
                            <?= $timer[0].":".sprintf("%02d", $timer[1]); ?>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</section>

<ul class="pagination-list">
    <li class="pagination-item pagination-item-prev">
        <?php if ($current_page_number != 1) : ?>
        <a href="?page=<?= ($current_page_number - 1) ?>&search=<?= $search?>">Назад</a></li>
        <?php endif; ?>

    <?php for ($i = 1;$i <= $total_pages_count; $i++) : ?>
    <li class="number pagination-item<?= $current_page_number == $i ? '-active' : '' ?>">
        <a href="?page=<?= $i ?>&search=<?= $search?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>

    <li class="pagination-item pagination-item-next">
        <?php if ($current_page_number != $total_pages_count) : ?>
        <a href="?page=<?= ($current_page_number + 1) ?>&search=<?= $search?>">Вперед</a></li>
        <?php endif; ?>
</ul>
