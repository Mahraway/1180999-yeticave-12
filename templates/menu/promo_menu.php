<ul class="promo__list">
    <?php foreach($categories as $category): ?>
        <li class="promo__item promo__item--<?= $category['code']?>">
            <a class="promo__link" href="pages/all-lots.html"><?= $category['name'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>
