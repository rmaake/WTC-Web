CREATE TABLE watched_movies (
    `Id` INT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `Movie_Name` VARCHAR (255) NOT NULL,
    `Server_Dir` VARCHAR (255) NOT NULL,
    `User_Id` VARCHAR(15) NOT NULL,
    `reg_date` TIMESTAMP
    )