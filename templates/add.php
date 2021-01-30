<!DOCTYPE html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?> | Добавление лота</title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/flatpickr.min.css" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">

    <header class="main-header">
        <div class="main-header__container container">
            <h1 class="visually-hidden">YetiCave</h1>
            <a class="main-header__logo" href="index.html">
                <img src="../img/logo.svg" width="160" height="39" alt="Логотип компании YetiCave">
            </a>
            <form class="main-header__search" method="get" action="https://echo.htmlacademy.ru" autocomplete="off">
                <input type="search" name="search" placeholder="Поиск лота">
                <input class="main-header__search-btn" type="submit" name="find" value="Найти">
            </form>
            <a class="main-header__add-lot button" href="add-lot.html">Добавить лот</a>
            <nav class="user-menu">
                <div class="user-menu__logged">
                    <p><?= $user_name ?></p>
                    <a class="user-menu__bets" href="my-bets.html">Мои ставки</a>
                    <a class="user-menu__logout" href="#">Выход</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <nav class="nav">
            <ul class="nav__list container">
                <?php foreach ($categories as $category) : ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= $category['name'] ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <form class="form form--add-lot container <?= !empty($error) ? 'form--invalid' : '' ?>" action="add.php" method="post" enctype="multipart/form-data">
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

                <span class="form__input-file file_name">
                    <?= $_FILES['image']['name'] ?? ''?>
                </span>

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

    </main>

</div>

<footer class="main-footer">
    <?= $footer ?>
</footer>

<script src="../flatpickr.js"></script>
<script src="../script.js"></script>
</body>
</html>
