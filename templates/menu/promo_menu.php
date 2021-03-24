<?php
/**
 * @var array $categories массив с категориями
 */
?>

<ul class="promo__list">
    <?php foreach ($categories as $category): ?>
        <li class="promo__item promo__item--<?= $category['code'] ?>">
            <a class="promo__link" href="/all-lots.php/?id=<?= $category['id'] ?>"><?= $category['name'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>
