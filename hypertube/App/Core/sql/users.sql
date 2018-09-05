CREATE TABLE users (
    `User_Id` VARCHAR (15) PRIMARY KEY,
    `First_Name` VARCHAR (255) NOT NULL,
    `Last_Name` VARCHAR (255) NOT NULL,
    `Email` VARCHAR (50) NOT NULL,
    `Pro_Pic` VARCHAR(255) DEFAULT '../public/resources/no_pic.jpg',
    `Password` VARCHAR(255) NOT NULL,
    `Veri_Code` VARCHAR(255) NULL,
    `reg_date` TIMESTAMP
    )