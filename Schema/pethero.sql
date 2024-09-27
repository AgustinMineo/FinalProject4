CREATE DATABASE petheros;

Use petheros;

CREATE TABLE `Roles`(
  `roleID` TINYINT(1) NOT NULL,
  `roleName` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`roleID`)
)ENGINE=InnoDB;

CREATE TABLE `User` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(30) DEFAULT NULL,
  `lastName` varchar(30) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `cellphone` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `userDescription` varchar(255) DEFAULT NULL,
  `questionRecovery` varchar(80) DEFAULT NULL,
  `answerRecovery` varchar(120) DEFAULT NULL,
  `roleID` tinyint(1) NOT NULL,
  CONSTRAINT FK_UserRole FOREIGN KEY (`roleID`) REFERENCES `Roles`(`roleID`),
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB;

CREATE TABLE `Owner` (
  `ownerID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL references User(userID),
  `petAmount` int(30) DEFAULT NULL,
  PRIMARY KEY (`ownerID`)
) ENGINE=InnoDB;

CREATE TABLE `Keeper` (
  `keeperID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL references User(userID),
  `animalSize` varchar(30) DEFAULT NULL,
  `price` DECIMAL DEFAULT NULL,
  `cbu` varchar (20) UNIQUE KEY, 
  `rank` DECIMAL DEFAULT 0,
  PRIMARY KEY (`keeperID`)
) ENGINE=InnoDB;

CREATE TABLE `Breed` (
  `breedID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL UNIQUE KEY,
  PRIMARY KEY (`breedID`)
) ENGINE=InnoDB;

CREATE TABLE `Pet` (
  `petID` int(11) NOT NULL AUTO_INCREMENT,
  `ownerID` int(11) NOT NULL references Owner(ownerID),
  `breedID` int(11) NOT NULL references Breed(breedID),
  `petDetails` varchar(255) DEFAULT NULL,
  `petName` varchar(30) DEFAULT NULL,
  `petSize` varchar(20) NOT NULL,
  `petVideo` VARCHAR(255) NOT NULL,
  `petImage` VARCHAR(255) NOT NULL,
  `petVaccinationPlan` VARCHAR(255) NOT NULL,
  `petWeight` varchar(20) DEFAULT NULL,
  `petAge` int DEFAULT NULL,
  PRIMARY KEY (`petID`)
) ENGINE=InnoDB;

/* Nuevo esquema  */
CREATE TABLE `Booking` (
  `bookingID` int(11) NOT NULL AUTO_INCREMENT,
  `keeperID` int(11) NOT NULL references Keeper(keeperID),
  `petID` int(11) NOT NULL references Pet(petID),
  `status` int(11) NOT NULL references Status(statusID),
  `totalValue` float,
  `amountReservation` float,
  `startDate` DATE NOT NULL, -- Fecha de inicio de la reserva
  `endDate` DATE NOT NULL,   -- Fecha de fin de la reserva
  PRIMARY KEY (`bookingID`)
) ENGINE=InnoDB;

CREATE TABLE `KeeperDays` (
  `keeperDaysID` int(11) NOT NULL AUTO_INCREMENT,
  `keeperID` int(11) NOT NULL references Keeper(keeperID),
  `day` DATE NOT NULL, -- Dia
  `available` BOOLEAN NOT NULL DEFAULT TRUE,  -- True es disponible
  PRIMARY KEY (`keeperDaysID`, `keeperID`, `day`)
) ENGINE=InnoDB;


CREATE TABLE `Status`(
	`statusID` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(40) NOT NULL UNIQUE KEY,
    PRIMARY KEY (`statusID`)
) ENGINE=InnoDB;

CREATE TABLE `Review`(
	`reviewID` int(11) NOT NULL AUTO_INCREMENT,
    `description` varchar(255),
    `rank` tinyint(5),
    `bookingID` int(11) NOT NULL references Booking(bookingID),
    PRIMARY KEY (`reviewID`)
) ENGINE=InnoDB;



INSERT INTO `roles` (roleID, roleName) VALUES (1, 'Admin'), (2, 'Owner'), (3, 'Keeper');

/*                                INSERT DE STATUS EN LA TABLA STATUS                       */
INSERT INTO status VALUES ("1","Pending"),("2","Rejected"),("3","Waiting for Payment"),("4","Waiting for confirmation"),("5","Confirmed"),("6","Finish"),("7","Completed"),("8","Overdue");
/*                                INSERT DE STATUS EN LA TABLA STATUS                       */


/*                                    INSERT DE BREEDS EN TABLA BREED                       */
INSERT INTO breed VALUES 
("1", "Beagle"), 
("2", "Chihuahua"), 
("3", "Bulldog"), 
("4", "German Shepherd"),
("5", "Shih-tzu"), 
("6", "Dogo"), 
("7", "Golden Retriever"), 
("8", "Fox Terrier"), 
("9", "Whippet"),
("10", "Pinscher"), 
("11", "Cocker"), 
("12", "Shiba Inu"), 
("13", "Doberman"), 
("14", "Border Collie"), 
("15", "Yorkshire"),
("16", "Poodle"),
("17", "Rottweiler"),
("18", "Labrador Retriever"),
("19", "Pug"),
("20", "Siberian Husky"),
("21", "Boxer"),
("22", "Dalmatian"),
("23", "Maltese"),
("24", "Saint Bernard"),
("25", "Cavalier King Charles Spaniel"),
("26", "French Bulldog"),
("27", "Great Dane"),
("28", "Basenji"),
("29", "Akita"),
("30", "Alaskan Malamute"),
("31", "Samoyed"),
("32", "Basset Hound"),
("33", "Australian Shepherd"),
("34", "Pembroke Welsh Corgi"),
("35", "Bichon Frise"),
("36", "Papillon"),
("37", "Jack Russell Terrier"),
("38", "Weimaraner"),
("39", "Bull Terrier"),
("40", "Pekingese"),
("41", "Staffordshire Bull Terrier"),
("42", "Airedale Terrier"),
("43", "Cane Corso"),
("44", "English Setter"),
("45", "Saluki"),
("46", "Italian Greyhound"),
("47", "Portuguese Water Dog"),
("48", "Tibetan Mastiff"),
("49", "Chow Chow"),
("50", "Irish Wolfhound");
/*                                    INSERT DE BREEDS EN TABLA BREED                       */

/*Validacion para ver si los jobs estan activos (Por defecto no en mysql) */
SHOW VARIABLES LIKE 'event_scheduler';
SET GLOBAL event_scheduler = ON;

/*Job de actualización de estados*/
CREATE EVENT IF NOT EXISTS update_reservations_status
ON SCHEDULE EVERY 5 MINUTE
DO

UPDATE Booking
SET status = 6  
WHERE status = 5
AND endDate < NOW();
UPDATE Booking
SET status = 8
WHERE status IN (1, 3, 4)
AND endDate < NOW();