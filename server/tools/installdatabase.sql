-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Paź 2017, 14:32
-- Wersja serwera: 5.6.26
-- Wersja PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `gakomi`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `cityID` int(11) NOT NULL,
  `cityName` varchar(256) COLLATE utf8_polish_ci NOT NULL,
  `orderID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `lines`
--

CREATE TABLE IF NOT EXISTS `lines` (
  `lineID` int(11) NOT NULL,
  `lineName` varchar(1024) COLLATE utf8_polish_ci NOT NULL,
  `distanceValue` double NOT NULL,
  `orderID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int(11) NOT NULL,
  `token` char(32) COLLATE utf8_polish_ci NOT NULL,
  `numberOfCities` int(11) NOT NULL,
  `initialCityName` varchar(256) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `resultmileage`
--

CREATE TABLE IF NOT EXISTS `resultmileage` (
  `mileageID` int(11) NOT NULL,
  `mileageValue` double NOT NULL,
  `orderID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `resultroute`
--

CREATE TABLE IF NOT EXISTS `resultroute` (
  `routeID` int(11) NOT NULL,
  `cityName` varchar(256) COLLATE utf8_polish_ci NOT NULL,
  `orderID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`cityID`),
  ADD KEY `orderID` (`orderID`);

--
-- Indexes for table `lines`
--
ALTER TABLE `lines`
  ADD PRIMARY KEY (`lineID`),
  ADD KEY `orderID` (`orderID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `resultmileage`
--
ALTER TABLE `resultmileage`
  ADD PRIMARY KEY (`mileageID`),
  ADD KEY `orderID` (`orderID`);

--
-- Indexes for table `resultroute`
--
ALTER TABLE `resultroute`
  ADD PRIMARY KEY (`routeID`),
  ADD KEY `orderID` (`orderID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `cities`
--
ALTER TABLE `cities`
  MODIFY `cityID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `lines`
--
ALTER TABLE `lines`
  MODIFY `lineID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `resultmileage`
--
ALTER TABLE `resultmileage`
  MODIFY `mileageID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `resultroute`
--
ALTER TABLE `resultroute`
  MODIFY `routeID` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

--
-- Ograniczenia dla tabeli `lines`
--
ALTER TABLE `lines`
  ADD CONSTRAINT `lines_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

--
-- Ograniczenia dla tabeli `resultmileage`
--
ALTER TABLE `resultmileage`
  ADD CONSTRAINT `resultmileage_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

--
-- Ograniczenia dla tabeli `resultroute`
--
ALTER TABLE `resultroute`
  ADD CONSTRAINT `resultroute_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);
