CREATE DATABASE yeticave
	DEFAULT CHARacter SET UTF8
	DEFAULT COLLATE UTF8_GENERAL_CI;

USE yeticave;


CREATE TABLE categories (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name CHAR
);

CREATE TABLE lots (
	id INT AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP,
	name CHAR,
	description CHAR,
	image CHAR,
	price INT,
	dt_end TIMESTAMP,
	step INT
);

CREATE TABLE bets (
	id INT AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP,
	price INT
);

CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP,
	name CHAR,
	email CHAR,
	password CHAR,
	contacts TEXT
);


