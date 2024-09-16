CREATE DATABASE petheros;

Use petheros;

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
  `price` float DEFAULT NULL,
  `cbu` varchar (20) UNIQUE KEY, 
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
  `petVideo` blob NOT NULL,
  `petImage` blob NOT NULL,
  `petVaccinationPlan` blob NOT NULL,
  `petWeight` varchar(20) DEFAULT NULL,
  `petAge` int DEFAULT NULL,
  PRIMARY KEY (`petID`)
) ENGINE=InnoDB;

CREATE TABLE `Booking` (
  `bookingID` int(11) NOT NULL AUTO_INCREMENT,
  `keeperID` int(11) NOT NULL references Keeper(keeperID),
  `petID` int(11) NOT NULL references Pet(petID),
  `status` tinyint(7) NOT NULL,
  `animalSize` varchar(30) ,
  `totalValue` float,
  `amountReservation` float,
  PRIMARY KEY (`bookingID`)
) ENGINE=InnoDB;

CREATE TABLE `KeeperDays` (
  `keeperDaysID` int(11) NOT NULL AUTO_INCREMENT,
  `keeperID` int(11) NOT NULL references Keeper(keeperID),
  `firstDate` date DEFAULT NULL,
  `lastDate` date DEFAULT NULL,
  PRIMARY KEY (`keeperDaysID`)
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

/*                                INSERT DE STATUS EN LA TABLA STATUS                       */
INSERT INTO status VALUES ("1","Pending"),("2","Rejected"),("3","Waiting for Payment"),("4","Waiting for confirmation"),("5","Confirmed"),("6","Finish"),("7","Completed");
/*                                INSERT DE STATUS EN LA TABLA STATUS                       */


/*                                    INSERT DE BREEDS EN TABLA BREED                       */
INSERT INTO breed VALUES ("1","Beagle"), ("2", "Chihuahua"), ("3","Bulldog"),("4", "German Shepherd"),
("5", "Shih-tzu"), ("6", "Dogo"), ("7", "Golden Retriever"), ("8","Fox Terrier"), ("9","Whippet"),
("10","Pinscher"), ("11","Cocker"), ("12","Shiba Inu"), ("13","Doberman"), ("14","Border Collie"), ("15","Yorkshire");
/*                                    INSERT DE BREEDS EN TABLA BREED                       */
