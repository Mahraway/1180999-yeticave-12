-- Создание базы данных
CREATE DATABASE yeticave
	DEFAULT CHARacter SET UTF8
	DEFAULT COLLATE UTF8_GENERAL_CI;

-- Используем базу данных
USE yeticave;

-- Создание таблицы категорий
CREATE TABLE categories (
	category_id INT AUTO_INCREMENT, 
	name VARCHAR(255) NOT NULL, 
	simbloic VARCHAR(255) NOT NULL,
	PRIMARY KEY (category_id) -- указываю первичный ключ
);

CREATE TABLE users (
	user_id INT AUTO_INCREMENT,
	dt_add DATETIME NOT NULL,
	name VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL UNIQUE, -- поле с проверкой на уникальность
	pass VARCHAR(255) NOT NULL,
	contacts VARCHAR(255) NOT NULL,
	PRIMARY KEY (user_id) -- указываю первичный ключ
);

CREATE TABLE lots (
	lot_id INT AUTO_INCREMENT,
	user_id INT NOT NULL,
	category_id INT NOT NULL,
	dt_add DATETIME NOT NULL,
	name VARCHAR(255) NOT NULL,
	description VARCHAR(255) NOT NULL,
	image VARCHAR(255) NOT NULL,
	price INT NOT NULL,
	dt_end TIMESTAMP NOT NULL,
	step INT NOT NULL,
	PRIMARY KEY (lot_id), 											-- указываю первичный ключ
	FOREIGN KEY (category_id) REFERENCES categories (category_id),  -- указываю внешний ключ для поля
	FOREIGN KEY (user_id) REFERENCES users (user_id),				-- указываю внешний ключ для поля
	INDEX NIndex (name),											-- создаю индекс для поля, по которому будет поиск
	INDEX CIndex (category_id)										-- создаю индекс для поля, по которому будет поиск
);

CREATE TABLE bets (
	bet_id INT AUTO_INCREMENT,
	user_id INT NOT NULL,
	lot_id INT NOT NULL,
	dt_add DATETIME,
	price INT NOT NULL,
	PRIMARY KEY (bet_id), 								-- указываю первичный ключ
	FOREIGN KEY (user_id) REFERENCES users (user_id),	-- указываю внешний ключ для поля
	FOREIGN KEY (lot_id) REFERENCES lots (lot_id)		-- указываю внешний ключ для поля
);
 

