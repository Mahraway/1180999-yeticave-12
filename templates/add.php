<!DOCTYPE html>
<html lang="ru">
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
        <form class="form form--add-lot container form--invalid" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
            <h2>Добавление лота</h2>
            <div class="form__container-two">
                <div class="form__item form__item--invalid"> <!-- form__item--invalid -->
                    <label for="lot-name">Наименование <sup>*</sup></label>
                    <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= get_post_value('lot-name') ?>" required>
                    <span class="form__error">Введите наименование лота</span>
                </div>
                <div class="form__item">
                    <label for="category">Категория <sup>*</sup></label>
                    <select id="category" name="category" required>
                        <option disabled value="default">Выберите категорию</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['id'] ?>" <?= get_post_select($category['id']) ?>><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form__error">Выберите категорию</span>
                </div>
            </div>
            <div class="form__item form__item--wide">
                <label for="message">Описание <sup>*</sup></label>
                <textarea id="message" name="message" placeholder="Напишите описание лота" required><?= get_post_value('message') ?></textarea>
                <span class="form__error">Напишите описание лота</span>
            </div>
            <div class="form__item form__item--file">
                <label>Изображение <sup>*</sup></label>
                <div class="form__input-file">
                    <input class="visually-hidden" type="file" id="lot-img" name="lot-img" required>
                    <label for="lot-img">
                        Добавить
                    </label>
                </div>
            </div>
            <div class="form__container-three">
                <div class="form__item form__item--small">
                    <label for="lot-rate">Начальная цена <sup>*</sup></label>
                    <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= get_post_value('lot-rate') ?>" required>
                    <span class="form__error">Введите начальную цену</span>
                </div>
                <div class="form__item form__item--small">
                    <label for="lot-step">Шаг ставки <sup>*</sup></label>
                    <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= get_post_value('lot-step') ?>" required>
                    <span class="form__error">Введите шаг ставки</span>
                </div>
                <div class="form__item">
                    <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                    <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= get_post_value('lot-date') ?>" required>
                    <span class="form__error">Введите дату завершения торгов</span>
                </div>
            </div>
            <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
            <button type="submit" class="button" name="my_form">Добавить лот</button>
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
