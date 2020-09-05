    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
           
        <!--заполните этот список из массива категорий-->
        <ul class="promo__list">
        <?php foreach($categories as $category): ?>      
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?= $category; ?></a>
            </li>
        <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <?php foreach($lots as $lot => $val): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $val['img']?>" width="350" height="260" alt="<?= $val['name']?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($val['categories']) ?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= htmlspecialchars($val['name'])?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= format_price($val['price']); ?></span>
                        </div>
                        <!-- Формаирование класса с красной плашкой -->
                        <?php 
                        $timer = get_time_before($val['time_left']);
                        if ($timer['1'] < 10) {
                            $time_finishing_class = 'timer--finishing';
                        } else {
                            $time_finishing_class = '';
                            }
                        ?>
                        <!-- Вывод таймера лота -->
                        <div class="lot__timer timer <?= $time_finishing_class; ?>">   
                        <?= $timer['0'].":".sprintf("%02d", $timer['1']); ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>