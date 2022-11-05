CREATE DATABASE petheros;

Use petheros;

CREATE TABLE `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(30) DEFAULT NULL,
  `lastName` varchar(30) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `cellphone` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB;