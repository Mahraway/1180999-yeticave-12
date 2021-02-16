<?php
/**
 * @var array $categories массив с категориями
 */
?>

<form
    class="form form--add-lot container <?= !empty($error) ? 'form--invalid' : '' ?>" action="add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= isset($error['name']) ? 'form__item--invalid' : '' ?>">
            <label for="name">Наименование <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите наименование лота" value="<?= get_post_value('name') ?>">
            <span class="form__error"><?= $error['name'] ?? '' ?></span>
        </div>
        <div class="form__item <?= isset($error['category_id']) ? 'form__item--invalid' : '' ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category_id" >
                <option value="">Выберите категорию</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'] ?>" <?= get_post_select($category['id']) ?>><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?= $error['category_id'] ?? '' ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?= isset($error['description']) ? 'form__item--invalid' : '' ?>">
        <label for="description">Описание <sup>*</sup></label>
        <textarea id="description" name="description" placeholder="Напишите описание лота" ><?= get_post_value('description') ?></textarea>
        <span class="form__error"><?= $error['description'] ?? '' ?></span>
    </div>
    <div class="form__item form__item--file <?= isset($error['image']) ? 'form__item--invalid' : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="image" name="image" >
            <label for="image">
                Добавить
            </label>
        </div>
        <span class="form__input-file file_name"><?= $_FILES['image']['name'] ?? ''?></span>
        <span class="form__error"><?= $error['image'] ?? ''?></span>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?= isset($error['price']) ? 'form__item--invalid' : '' ?>">
            <label for="price">Начальная цена <sup>*</sup></label>
            <input id="price" type="text" name="price" placeholder="0" value="<?= get_post_value('price') ?>">
            <span class="form__error"><?= $error['price'] ?? ''?></span>
        </div>
        <div class="form__item form__item--small <?= isset($error['step']) ? 'form__item--invalid' : '' ?>">
            <label for="step">Шаг ставки <sup>*</sup></label>
            <input id="step" type="text" name="step" placeholder="0" value="<?= get_post_value('step') ?>">
            <span class="form__error"><?= $error['step'] ?? ''?></span>
        </div>
        <div class="form__item <?= isset($error['dt_end']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="dt_end" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= get_post_value('dt_end') ?>">
            <span class="form__error"><?= $error['dt_end'] ?? '' ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button" name="add_lot_form">Добавить лот</button>
</form>

