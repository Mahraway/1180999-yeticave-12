<section class="lots">
    <h2><span>Результат поиска по запросу "<?= $_GET['search'] ?? ''?>"</span></h2>
    <?= $message ?? '' ?>
    <ul class="lots__list">
        <?php foreach ($lots as $lot) : ?>
        <li class="lots__item lot">
            <?= 'Score: ' . round($lot['score'], 2) ?>
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
    <li class="pagination-item pagination-item-prev"><a href="">Назад</a></li>
    <!--pagination-item-active-->
    <?php for ($i = 1;$i <= $page_count; $i++) : ?>
    <li class="pagination-item"><a href="?page=<?= $i ?>&search=<?= $_GET['search'] ?>"><?= $i ?></a></li>
    <?php endfor; ?>
    <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
</ul>
