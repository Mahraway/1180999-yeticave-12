CREATE DATABASE yeticave
	DEFAULT CHARacter SET UTF8
	DEFAULT COLLATE UTF8_GENERAL_CI;

USE yeticave;

CREATE TABLE categories (
	id INT AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	code VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE users (
	id INT AUTO_INCREMENT,
	dt_add DATETIME NOT NULL,
	name VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	contacts VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE lots (
	id INT AUTO_INCREMENT,
	user_id INT NOT NULL,
	category_id INT NOT NULL,
	name VARCHAR(255) NOT NULL,
	description VARCHAR(1000) NOT NULL,
	image VARCHAR(255) NOT NULL,
	price INT NOT NULL,
	step INT NOT NULL,
	dt_add DATETIME NOT NULL,
	dt_end DATETIME NOT NULL,
	PRIMARY KEY (id),
	INDEX lots_name_idx (name),
	INDEX lots_category_idx (category_id),
	INDEX lots_user_idx (user_id),
	FOREIGN KEY (category_id) REFERENCES categories (id),
	FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE bets (
	id INT AUTO_INCREMENT,
	user_id INT NOT NULL,
	lot_id INT NOT NULL,
	dt_add DATETIME,
	price INT NOT NULL,
	PRIMARY KEY (id),
	INDEX bets_user_idx (user_id),
	INDEX bets_lot_idx (lot_id),
	FOREIGN KEY (user_id) REFERENCES users (id),
	FOREIGN KEY (lot_id) REFERENCES lots (id)
);


