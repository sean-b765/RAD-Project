CREATE DATABASE IF NOT EXISTS movie_db;

CREATE TABLE IF NOT EXISTS movies (
    ID INT(10) NOT NULL AUTO_INCREMENT,
    Name VARCHAR(32) NOT NULL,
    Email VARCHAR(32) NOT NULL,
    PRIMARY KEY(ID)
);
