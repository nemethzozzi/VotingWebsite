-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Nov 03. 13:27
-- Kiszolgáló verziója: 10.4.28-MariaDB
-- PHP verzió: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `szavazatszamlalo`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalo`
--

CREATE TABLE `felhasznalo` (
  `Felhasznalonev` varchar(20) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Jelszo` varchar(60) NOT NULL,
  `Legutobbi belepes` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `felhasznalo`
--

INSERT INTO `felhasznalo` (`Felhasznalonev`, `Email`, `Jelszo`, `Legutobbi belepes`) VALUES
('zozzi', '11zozzi11@gmail.com', '$2y$10$gyu4U5nAQ6mVbopUAj6BpuYb7OF9VXlFy0l1BI9W2XmsYQCMmpVFi', '2023-11-03'),
('asd', 'asd@mail.com', '$2y$10$0VoFppYuCKJ9LLjJpOWPgOzgtk5a89yttLNxIrSvLQOje4oJ/MJdq', '2023-11-03'),
('asd2', 'asd2@mail.com', '$2y$10$NJcs5tn8jkEYyTA4SobfaOfzS8SbnpREVDwjDums9lHDTWoYcwDqW', '2023-11-03'),
('Proba', 'proba@mail.com', '$2y$10$Zg8TvX3ajqADZWHxS0GOeuXYUNTpF7zoMl6FYIE9ngLoKIpt9LP2q', '2023-11-01'),
('Proba2', 'proba2@mail.com', '$2y$10$YA.yD5TWUGwNAkt82m002edQi/.6Z3pG9ahGBlnMkE5vZrSAO5JZu', '2023-11-01');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jelolt`
--

CREATE TABLE `jelolt` (
  `Jelolt kod` int(6) NOT NULL,
  `Nev` varchar(50) NOT NULL,
  `Szuletesi datum` date NOT NULL,
  `Foglalkozas` varchar(100) NOT NULL,
  `Program` varchar(100) NOT NULL,
  `Szavazas kod` int(6) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `jelolt`
--

INSERT INTO `jelolt` (`Jelolt kod`, `Nev`, `Szuletesi datum`, `Foglalkozas`, `Program`, `Szavazas kod`, `Email`) VALUES
(1, '1Jelolt', '2000-10-11', 'ASD', 'asd', 28, 'asd@mail.com'),
(3, '2Jelolt', '2005-10-10', 'adasd', 'asdasd', 29, 'asd2@mail.com'),
(4, '3Jelolt', '1980-04-22', 'asdad', 'asdas', 30, 'asd2@mail.com'),
(6, '3Hozzaadott', '2000-02-20', 'asdsad', 'asdasdd', 31, '11zozzi11@gmail.com');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szavazas`
--

CREATE TABLE `szavazas` (
  `Megnevezes` varchar(20) NOT NULL,
  `Leiras` varchar(100) NOT NULL,
  `Jeloltek` varchar(20) NOT NULL,
  `Indul` date NOT NULL,
  `Zarul` date NOT NULL,
  `Szavazas kod` int(6) NOT NULL,
  `Email` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `szavazas`
--

INSERT INTO `szavazas` (`Megnevezes`, `Leiras`, `Jeloltek`, `Indul`, `Zarul`, `Szavazas kod`, `Email`) VALUES
('1Szavazas', 'asdasd', '1Jelolt', '2023-10-10', '2025-12-10', 28, 'asd@mail.com'),
('2Szavazas', 'ASDASDASDSAd', '2Jelolt', '2021-12-31', '2022-12-30', 29, 'asd2@mail.com'),
('3Szavazas', 'asdasd', '3Jelolt', '2023-10-17', '2023-10-20', 30, 'asd2@mail.com'),
('UjSzavazas', 'asdasdas', '3Hozzaadott', '2023-10-20', '2023-11-10', 31, '11zozzi11@gmail.com');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szavazat`
--

CREATE TABLE `szavazat` (
  `Felhasznalonev` varchar(30) NOT NULL,
  `Melyik szavazas` varchar(20) NOT NULL,
  `Melyik jeloltre` varchar(50) NOT NULL,
  `Idopont` date NOT NULL,
  `Szavazat kod` int(6) NOT NULL,
  `Szavazas kod` int(6) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `szavazat`
--

INSERT INTO `szavazat` (`Felhasznalonev`, `Melyik szavazas`, `Melyik jeloltre`, `Idopont`, `Szavazat kod`, `Szavazas kod`, `Email`) VALUES
('asd', '28', '1Jelolt', '2023-11-03', 1, 28, 'asd@mail.com'),
('asd2', '28', '1Hozzaadott', '2023-11-03', 2, 28, 'asd2@mail.com'),
('asd2', '29', '2Jelolt', '2023-11-03', 3, 29, 'asd2@mail.com'),
('zozzi', '31', '3Hozzaadott', '2023-11-03', 4, 31, '11zozzi11@gmail.com');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `felhasznalo`
--
ALTER TABLE `felhasznalo`
  ADD PRIMARY KEY (`Email`),
  ADD UNIQUE KEY `Felhasznalonev` (`Felhasznalonev`,`Email`);

--
-- A tábla indexei `jelolt`
--
ALTER TABLE `jelolt`
  ADD PRIMARY KEY (`Jelolt kod`),
  ADD UNIQUE KEY `Jelolt kod` (`Jelolt kod`,`Szavazas kod`,`Email`),
  ADD KEY `Email` (`Email`),
  ADD KEY `Szavazas kod` (`Szavazas kod`);

--
-- A tábla indexei `szavazas`
--
ALTER TABLE `szavazas`
  ADD PRIMARY KEY (`Szavazas kod`),
  ADD UNIQUE KEY `Szavazas kod_2` (`Szavazas kod`),
  ADD UNIQUE KEY `Szavazas kod` (`Szavazas kod`,`Email`),
  ADD KEY `Email` (`Email`);

--
-- A tábla indexei `szavazat`
--
ALTER TABLE `szavazat`
  ADD PRIMARY KEY (`Szavazat kod`),
  ADD UNIQUE KEY `Szavazat kod` (`Szavazat kod`,`Szavazas kod`,`Email`),
  ADD KEY `Email` (`Email`),
  ADD KEY `Szavazas kod` (`Szavazas kod`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `jelolt`
--
ALTER TABLE `jelolt`
  MODIFY `Jelolt kod` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT a táblához `szavazas`
--
ALTER TABLE `szavazas`
  MODIFY `Szavazas kod` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT a táblához `szavazat`
--
ALTER TABLE `szavazat`
  MODIFY `Szavazat kod` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `jelolt`
--
ALTER TABLE `jelolt`
  ADD CONSTRAINT `jelolt_ibfk_2` FOREIGN KEY (`Email`) REFERENCES `felhasznalo` (`Email`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `jelolt_ibfk_3` FOREIGN KEY (`Szavazas kod`) REFERENCES `szavazas` (`Szavazas kod`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Megkötések a táblához `szavazas`
--
ALTER TABLE `szavazas`
  ADD CONSTRAINT `szavazas_ibfk_1` FOREIGN KEY (`Email`) REFERENCES `felhasznalo` (`Email`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Megkötések a táblához `szavazat`
--
ALTER TABLE `szavazat`
  ADD CONSTRAINT `szavazat_ibfk_1` FOREIGN KEY (`Email`) REFERENCES `felhasznalo` (`Email`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `szavazat_ibfk_2` FOREIGN KEY (`Szavazas kod`) REFERENCES `szavazas` (`Szavazas kod`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
