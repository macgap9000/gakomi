SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `cities` (
  `cityID` int(11) NOT NULL,
  `cityName` varchar(256) COLLATE utf8_polish_ci NOT NULL,
  `orderID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE IF NOT EXISTS `lines` (
  `lineID` int(11) NOT NULL,
  `lineName` varchar(1024) COLLATE utf8_polish_ci NOT NULL,
  `distanceValue` double NOT NULL,
  `orderID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int(11) NOT NULL,
  `token` char(32) COLLATE utf8_polish_ci NOT NULL,
  `numberOfCities` int(11) NOT NULL,
  `initialCityName` varchar(256) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE IF NOT EXISTS `resultmileage` (
  `mileageID` int(11) NOT NULL,
  `mileageValue` double NOT NULL,
  `orderID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE IF NOT EXISTS `resultroute` (
  `routeID` int(11) NOT NULL,
  `cityName` varchar(256) COLLATE utf8_polish_ci NOT NULL,
  `orderID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


ALTER TABLE `cities`
  ADD PRIMARY KEY (`cityID`),
  ADD KEY `orderID` (`orderID`);

ALTER TABLE `lines`
  ADD PRIMARY KEY (`lineID`),
  ADD KEY `orderID` (`orderID`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD UNIQUE KEY `token` (`token`);

ALTER TABLE `resultmileage`
  ADD PRIMARY KEY (`mileageID`),
  ADD KEY `orderID` (`orderID`);

ALTER TABLE `resultroute`
  ADD PRIMARY KEY (`routeID`),
  ADD KEY `orderID` (`orderID`);


ALTER TABLE `cities`
  MODIFY `cityID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `lines`
  MODIFY `lineID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `resultmileage`
  MODIFY `mileageID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `resultroute`
  MODIFY `routeID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

ALTER TABLE `lines`
  ADD CONSTRAINT `lines_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

ALTER TABLE `resultmileage`
  ADD CONSTRAINT `resultmileage_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

ALTER TABLE `resultroute`
  ADD CONSTRAINT `resultroute_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);
