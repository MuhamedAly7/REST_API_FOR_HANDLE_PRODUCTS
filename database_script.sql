CREATE DATABASE product_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE product_db;

CREATE TABLE product (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    size INT NOT NULL DEFAULT 0,
    is_available BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO product (name, size, is_available) 
VALUES ('Product1', 10, TRUE), ('Product2', 2, TRUE), ('Product3', 1, FALSE);