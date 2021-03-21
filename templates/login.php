
<form class="form container <?= !empty($error) ? 'form--invalid' : '' ?>" action="/login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?= isset($error['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $form_data['email'] ?? '' ?>">
        <span class="form__error"><?= $error['email'] ?? '' ?></span>
    </div>
    <div class="form__item form__item--last <?= isset($error['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= $form_data['password'] ?? '' ?>">
        <span class="form__error"><?= $error['password'] ?? '' ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>

