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
                <div class="form__item <?= isset($error['lot-name']) ? 'form__item--invalid' : '' ?>">
                    <label for="lot-name">Наименование <sup>*</sup></label>
                    <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= get_post_value('lot-name') ?>">
                    <span class="form__error"><?= $error['lot-name'] ?? '' ?></span>
                </div>
                <div class="form__item <?= isset($error['category']) ? 'form__item--invalid' : '' ?>">
                    <label for="category">Категория <sup>*</sup></label>
                    <select id="category" name="category" >
                        <option disabled value="default">Выберите категорию</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['id'] ?>" <?= get_post_select($category['id']) ?>><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form__error"><?= $error['category'] ?? '' ?></span>
                </div>
            </div>
            <div class="form__item form__item--wide <?= isset($error['message']) ? 'form__item--invalid' : '' ?>">
                <label for="message">Описание <sup>*</sup></label>
                <textarea id="message" name="message" placeholder="Напишите описание лота" ><?= get_post_value('message') ?></textarea>
                <span class="form__error"><?= $error['message'] ?? '' ?></span>
            </div>
            <div class="form__item form__item--file <?= isset($error['lot-img']) ? 'form__item--invalid' : '' ?>">
                <label>Изображение <sup>*</sup></label>
                <div class="form__input-file">
                    <input class="visually-hidden" type="file" id="lot-img" name="lot-img" >
                    <label for="lot-img">
                        Добавить
                    </label>
                </div>

<!--Комментарии для наставника:  Тут я указал вывод названия файла после добавления-->
                <span class="form__input-file file_name">
                    <?= $_FILES['lot-img']['name'] ?? ''?>
                </span>

                <span class="form__error"><?= $error['lot-img'] ?? ''?></span>
            </div>

            <div class="form__container-three">
                <div class="form__item form__item--small <?= isset($error['lot-rate']) ? 'form__item--invalid' : '' ?>">
                    <label for="lot-rate">Начальная цена <sup>*</sup></label>
                    <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= get_post_value('lot-rate') ?>">
                    <span class="form__error"><?= $error['lot-rate'] ?? ''?></span>
                </div>
                <div class="form__item form__item--small <?= isset($error['lot-step']) ? 'form__item--invalid' : '' ?>">
                    <label for="lot-step">Шаг ставки <sup>*</sup></label>
                    <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= get_post_value('lot-step') ?>">
                    <span class="form__error"><?= $error['lot-step'] ?? ''?></span>
                </div>
                <div class="form__item <?= isset($error['lot-date']) ? 'form__item--invalid' : '' ?>">
                    <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                    <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= get_post_value('lot-date') ?>">
                    <span class="form__error"><?= $error['lot-date'] ?? '' ?></span>
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
