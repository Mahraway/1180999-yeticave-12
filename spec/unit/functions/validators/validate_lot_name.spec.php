<?php

describe("Функция validate_lot_name", function() {

    context("при передаче корректного имени лота", function () {
        it("валидация проходит успешно", function() {
            $result = validate_lot_name('Лыжи для горного спуска');
            expect($result)->toBe(null);
        });
    });

    context("при передаче пустого имени лота", function () {
        it("возвращает ошибку", function() {
            $result = validate_lot_name('');
            expect($result)->toBe('Введите название лота');
        });
    });

    context("при превышении длины имени лота", function () {
        it("возвращает ошибку", function() {

            $template = '1234567890';
            $str = '';
            for ($i = 0; $i < 26; $i++) {
                $str .= $template;
            }

            $result = validate_lot_name($str);
            expect($result)->toBe('Введите не более 255 символов');
        });
    });

});
