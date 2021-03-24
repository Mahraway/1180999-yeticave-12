<?php

describe("Функция get_correct_timer", function() {

    context("Когда дата из будущего", function () {
        it("валидация проходит успешно", function () {
            $result = get_correct_timer(date('Y-m-d H:s:i', time() + 1));
            expect($result)->toBe(null);
        });
    });

    context("Когда дата текущая", function () {
        it("валидация проходит успешно", function () {
            $time = date('Y-m-d H:i:s', time());
            $result = get_correct_timer($time);
            expect($result)->toBe('Только что');
        });
    });

    context("Когда прошло 60 секунд", function () {
        it("валидация проходит успешно", function () {
            $time = date('Y-m-d H:i:s', time() - 60);
            $result = get_correct_timer($time);
            expect($result)->toBe('1 минуту назад');
        });
    });

    context("Когда прошел 1 час", function () {
        it("валидация проходит успешно", function () {
            $time = date('Y-m-d H:i:s', time() - 3600);
            $result = get_correct_timer($time);
            expect($result)->toBe('1 час назад');
        });
    });

    context("Когда прошел 24 часа", function () {
        it("валидация проходит успешно", function () {
            $time = date('Y-m-d H:i:s', time() - 3600 * 24);
            $result = get_correct_timer($time);
            expect($result)->toBe('Вчера');
        });
    });

    context("Когда прошел 48 часа", function () {
        it("валидация проходит успешно", function () {
            $time = date('Y-m-d H:i:s', time() - 3600 * 48);
            $result = get_correct_timer($time);
            expect($result)->toBe('Прошло более двух суток');
        });
    });
});
