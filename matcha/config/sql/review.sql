CREATE TABLE review (
    `Id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `User_Id` VARCHAR (15),
    `Liked` VARCHAR(3),
    `Visited` VARCHAR(3),
    `Blocked` VARCHAR(3),
    `Review` VARCHAR (15),
    `review_date` TIMESTAMP
)