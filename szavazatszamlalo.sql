-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Okt 30. 15:53
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
('asd', 'asd@mail.com', '$2y$10$0VoFppYuCKJ9LLjJpOWPgOzgtk5a89yttLNxIrSvLQOje4oJ/MJdq', '2023-10-30');

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
(1, 'Proba', '2000-10-10', 'asdasd', 'asdasd', NULL, 'asd@mail.com'),
(2, 'Proba2', '2000-10-10', 'asdasd', 'asdasd', NULL, 'asd@mail.com'),
(3, 'Proba3', '2000-10-10', 'asdasd', 'asdasd', 11, 'asd@mail.com'),
(4, 'Proba4', '2000-10-10', 'asdasd', 'asdasd', 11, 'asd@mail.com'),
(5, 'végre műkö', '2000-10-10', 'asdasd', 'asdas', 11, 'asd@mail.com');

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
('Proba', 'asdasd', 'Proba', '2023-10-30', '2030-10-10', 11, 'asd@mail.com');

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
('asd', '11', 'Proba', '2023-10-30', 6, NULL, 'asd@mail.com');

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
  MODIFY `Jelolt kod` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT a táblához `szavazas`
--
ALTER TABLE `szavazas`
  MODIFY `Szavazas kod` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT a táblához `szavazat`
--
ALTER TABLE `szavazat`
  MODIFY `Szavazat kod` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
