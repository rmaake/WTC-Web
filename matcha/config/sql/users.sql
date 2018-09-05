CREATE TABLE users (
    `User_Id` VARCHAR (15) PRIMARY KEY,
    `First_Name` VARCHAR (255) NOT NULL,
    `Last_Name` VARCHAR (255) NOT NULL,
    `Email` VARCHAR (50) NOT NULL,
    `Gender` VARCHAR(6) NULL,
    `Biography` MEDIUMTEXT NULL,
    `Interests` TEXT NULL,
    `Age` INT(3) NULL,
    `Sexuality` VARCHAR(100) NOT NULL DEFAULT 'Bisexual',
    `Rating` INT(3) NULL,
    `DOB` DATE,
    `Password` VARCHAR(255) NOT NULL,
    `Veri_Code` VARCHAR(255) NOT NULL,
    `Status` VARCHAR(500),
    `reg_date` TIMESTAMP
    )