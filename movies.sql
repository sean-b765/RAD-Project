CREATE DATABASE IF NOT EXISTS movie_db;

USE movie_db;

CREATE TABLE IF NOT EXISTS movies (
    ID INT(10) NOT NULL AUTO_INCREMENT,
    Title VARCHAR(32) NOT NULL,
    Studio VARCHAR(32) NOT NULL,
    Status VARCHAR(32) NOT NULL,
    Sound VARCHAR(32) NOT NULL,
    Versions VARCHAR(32) NOT NULL,
    RetailPrice FLOAT NOT NULL,
    Rating VARCHAR(16) NOT NULL,
    Year INT(10) NOT NULL,
    Genre VARCHAR(32) NOT NULL,
    Aspect VARCHAR(16) NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS top_searches (
	SearchID INT(10) NOT NULL AUTO_INCREMENT,
	ID INT(10) NOT NULL,
	Title VARCHAR(32) NOT NULL,
	SearchAmount INT(10) NOT NULL,
	PRIMARY KEY(SearchID),
	FOREIGN KEY(ID) REFERENCES movies(ID)
);