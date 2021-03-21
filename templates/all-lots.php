<?php
/**
 * @var string $message
 * @var string $lots_list
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
            <?= $lots_list ?>
        </ul>
    </section>

    <?php if ($count_total_founded_lots > $lots_per_page) : ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev ">
                <a class="<?= ($current_page_number === 1) ? 'page__item--hidden' : '' ?>"
                   href="?page=<?= ($current_page_number - 1) ?>&category=<?= $category ?>">Назад
                </a>
            </li>

            <?php for ($i = 1; $i <= $total_pages_count; $i++) : ?>
                <li class="number pagination-item <?= $current_page_number === $i ? 'pagination-item-active' : '' ?>">
                    <a href="?page=<?= $i ?>&category=<?= $category ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="pagination-item pagination-item-next ">
                <a class="<?= ($current_page_number === $total_pages_count) ? 'page__item--hidden' : '' ?>"
                   href="?page=<?= ($current_page_number + 1) ?>&category=<?= $category ?>">Вперед
                </a>
            </li>
        </ul>
    <?php endif; ?>



