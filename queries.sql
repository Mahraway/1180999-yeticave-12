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


-- Получить список категорий
SELECT id, name, code 
FROM categories; 

-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, текущую цену, название категории;
SELECT l.id, l.name, l.price, MAX(b.price) AS current_price , image, c.name AS category_name
FROM lots l
JOIN categories c ON c.id = l.category_id
LEFT JOIN bets b ON b.lot_id = l.id
WHERE l.dt_end > NOW()
GROUP BY (l.id)
ORDER BY l.dt_add DESC;

-- показать лот по его id. Получите также название категории, к которой принадлежит лот;
SELECT l.id, l.name, c.name AS category_name
FROM lots l
JOIN categories c ON l.category_id = c.id;

-- обновить название лота по его идентификатору;
UPDATE lots SET name = '2015 Rossignol District Snowboard'
WHERE id = 1;

-- получить список ставок для лота по его идентификатору с сортировкой по дате
SELECT *
FROM bets
ORDER BY dt_add DESC;

