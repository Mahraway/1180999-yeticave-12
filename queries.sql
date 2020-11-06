-- Создание категорий
INSERT INTO categories (name, code) VALUES
    ('Доски и лыжи', 'boards'),
	('Крепления', 'mounts'),
	('Ботинки','bots'),
	('Одежда','garb'),
	('Инструменты','tools'),
	('Разное','other');

-- Добавление пользователей
INSERT INTO users (dt_add, name, email, pass, contacts) VALUES
    (NOW(), 'Иван','ivan@mail.ru','123','Москва'),
    (NOW(), 'Петр','petr@mail.ru','321','Москва'),
    (NOW(), 'Олег','oleg@mail.ru','132','Ростов'),
    (NOW(), 'Сергей','sergei@mail.ru','312','Калининград'),
    (NOW(), 'Павел','pavel@mail.ru','213','Краснодар');

-- Добавление существующего списка лотов 
INSERT INTO lots (user_id, category_id, dt_add, name, description, image, price, dt_end, step) VALUES
    (1, 1, NOW(), '2014 Rossignol District Snowboard','Описание', 'img/lot-1.jpg', 10999, '2020-08-28', 1),
    (1, 1, NOW(), 'DC Ply Mens 2016/2017 Snowboard','Описание', 'img/lot-2.jpg', 159999, '2020-11-10 23:00', 1),
    (1, 2, NOW(), 'Крепления Union Contact Pro 2015 года размер L/XL','Описание', 'img/lot-3.jpg', 8000, '2020-12-07', 1),
    (2, 3, NOW(), 'Ботинки для сноуборда DC Mutiny Charocal','Описание', 'img/lot-4.jpg', 10999, '2020-12-11', 1),
    (2, 4, NOW(), 'Куртка для сноуборда DC Mutiny Charocal','Описание', 'img/lot-5.jpg', 7500, '2021-11-10', 1),
    (2, 4, NOW(), 'Маска Oakley Canopy','Описание', 'img/lot-6.jpg', 5400, '2021-01-11', 1);

-- Добавление ставок объявлениям
INSERT INTO bets (user_id, lot_id, dt_add, price) VALUES
    (3, 1, NOW(), 11000),
    (3, 2, NOW(), 160000),
    (4, 4, NOW(), 11000),
    (5, 4, NOW(), 11001);

-- Запросы на эти действия 

SELECT name FROM categories; -- Получить список категорий
SELECT name, price, image FROM lots WHERE dt_end > NOW() ORDER BY dt_add DESC; --открытые лоты, с сортировке по последним добавленным

