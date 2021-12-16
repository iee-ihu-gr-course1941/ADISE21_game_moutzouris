CREATE DATABASE moutzouris;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id int NOT NULL AUTO_INCREMENT,
    username varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO
    users
VALUES
    (default, 'panos', 'abc');

INSERT INTO
    users
VALUES
    (default, 'miltos', 'abc');