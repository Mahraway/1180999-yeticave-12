
<form class="form container <?= !empty($error) ? 'form--invalid' : '' ?>" action="/sign-up.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($error['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= get_post_value('email')?>">
        <span class="form__error"><?= $error['email'] ?? '' ?></span>
    </div>
    <div class="form__item <?= isset($error['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= get_post_value('password') ?>">
        <span class="form__error"><?= $error['password'] ?? '' ?></span>
    </div>
    <div class="form__item <?= isset($error['name']) ? 'form__item--invalid' : '' ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= get_post_value('name') ?>">
        <span class="form__error"><?= $error['name'] ?? '' ?></span>
    </div>
    <div class="form__item <?= isset($error['contacts']) ? 'form__item--invalid' : '' ?>">
        <label for="contacts">Контактные данные <sup>*</sup></label>
        <textarea id="contacts" name="contacts" placeholder="Напишите как с вами связаться"><?= get_post_value('contacts') ?></textarea>
        <span class="form__error"><?= $error['contacts'] ?? '' ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
