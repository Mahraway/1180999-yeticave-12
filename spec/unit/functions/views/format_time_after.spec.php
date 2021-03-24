<?php

describe("Функция get_correct_timer", function() {

    context("Когда дата из будущего", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $current_timestamp = time();
            $current_date_time = date('Y-m-d H:i:s', $current_timestamp);
            $event_date_time = date('Y-m-d H:i:s', $current_timestamp + 1);
            $result = format_time_after($current_date_time, $event_date_time);
            expect($result)->toBe('');
        });
    });

    context("Когда дата текущая", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $current_timestamp = time();
            $current_date_time = date('Y-m-d H:i:s', $current_timestamp);
            $event_date_time = date('Y-m-d H:i:s', $current_timestamp);
            $result = format_time_after($current_date_time, $event_date_time);
            expect($result)->toBe('Только что');
        });
    });

    context("Когда прошло 30 секунд", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $current_timestamp = time();
            $current_date_time = date('Y-m-d H:i:s', $current_timestamp);
            $event_date_time = date('Y-m-d H:i:s', $current_timestamp - MINUTE/2);
            $result = format_time_after($current_date_time, $event_date_time);
            expect($result)->toBe('30 секунд назад');
        });
    });

    context("Когда прошло 60 секунд", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $current_timestamp = time();
            $current_date_time = date('Y-m-d H:i:s', $current_timestamp);
            $event_date_time = date('Y-m-d H:i:s', $current_timestamp - MINUTE);
            $result = format_time_after($current_date_time, $event_date_time);
            expect($result)->toBe('1 минуту назад');
        });
    });

    context("Когда прошел 1 час", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $current_timestamp = time();
            $current_date_time = date('Y-m-d H:i:s', $current_timestamp);
            $event_date_time = date('Y-m-d H:i:s', $current_timestamp - HOUR);
            $result = format_time_after($current_date_time, $event_date_time);
            expect($result)->toBe('1 час назад');
        });
    });

    context("Когда прошло 24 часа", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $current_timestamp = time();
            $current_date_time = date('Y-m-d H:i:s', $current_timestamp);
            $event_date_time = date('Y-m-d H:i:s', $current_timestamp - DAY);
            $result = format_time_after($current_date_time, $event_date_time);
            expect($result)->toBe('1 день назад');
        });
    });

    context("Когда прошло 48 часов", function () {
        it("возвращает прошедший интервал времени в читаемом формате", function () {
            $current_timestamp = time();
            $current_date_time = date('Y-m-d H:i:s', $current_timestamp);
            $event_date_time = date('Y-m-d H:i:s', $current_timestamp - DAY * 2);
            $result = format_time_after($current_date_time, $event_date_time);
            expect($result)->toBe(date('y.m.d, в H:i', strtotime($event_date_time)));
        });
    });
});
