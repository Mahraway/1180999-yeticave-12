<?php

describe("Функция get_correct_timer", function() {

    context("Когда дата из будущего", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $currentTimestamp = time();
            $currentDateTime = date('Y-m-d H:i:s', $currentTimestamp);
            $eventDateTime = date('Y-m-d H:i:s', $currentTimestamp + 1);
            $result = format_time_after($currentDateTime, $eventDateTime);
            expect($result)->toBe('');
        });
    });

    context("Когда дата текущая", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $currentTimestamp = time();
            $currentDateTime = date('Y-m-d H:i:s', $currentTimestamp);
            $eventDateTime = date('Y-m-d H:i:s', $currentTimestamp);
            $result = format_time_after($currentDateTime, $eventDateTime);
            expect($result)->toBe('Только что');
        });
    });

    context("Когда прошло 30 секунд", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $currentTimestamp = time();
            $currentDateTime = date('Y-m-d H:i:s', $currentTimestamp);
            $eventDateTime = date('Y-m-d H:i:s', $currentTimestamp - MINUTE/2);
            $result = format_time_after($currentDateTime, $eventDateTime);
            expect($result)->toBe('30 секунд назад');
        });
    });

    context("Когда прошло 60 секунд", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $currentTimestamp = time();
            $currentDateTime = date('Y-m-d H:i:s', $currentTimestamp);
            $eventDateTime = date('Y-m-d H:i:s', $currentTimestamp - MINUTE);
            $result = format_time_after($currentDateTime, $eventDateTime);
            expect($result)->toBe('1 минуту назад');
        });
    });

    context("Когда прошел 1 час", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $currentTimestamp = time();
            $currentDateTime = date('Y-m-d H:i:s', $currentTimestamp);
            $eventDateTime = date('Y-m-d H:i:s', $currentTimestamp - HOUR);
            $result = format_time_after($currentDateTime, $eventDateTime);
            expect($result)->toBe('1 час назад');
        });
    });

    context("Когда прошло 24 часа", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $currentTimestamp = time();
            $currentDateTime = date('Y-m-d H:i:s', $currentTimestamp);
            $eventDateTime = date('Y-m-d H:i:s', $currentTimestamp - DAY);
            $result = format_time_after($currentDateTime, $eventDateTime);
            expect($result)->toBe('Вчера');
        });
    });

    context("Когда прошло 48 часов", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $currentTimestamp = time();
            $currentDateTime = date('Y-m-d H:i:s', $currentTimestamp);
            $eventDateTime = date('Y-m-d H:i:s', $currentTimestamp - DAY * 2);
            $result = format_time_after($currentDateTime, $eventDateTime);
            expect($result)->toBe('Прошло более двух суток');
        });
    });
});
