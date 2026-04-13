DROP DATABASE IF EXISTS `Valor`;

CREATE DATABASE  `valor`;

USE `valor`;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password_hash varchar(255),
    encrypted_salt VARBINARY(16)
);

CREATE TABLE passwords (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id int,
    site_name VARCHAR(200),
    encrypted_data VARBINARY(256),
    iv VARBINARY(16),
    tag VARBINARY(16),
    FOREIGN KEY (user_id) REFERENCES users(id)
);