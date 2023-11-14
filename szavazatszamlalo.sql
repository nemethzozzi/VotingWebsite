-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Nov 14. 12:48
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
('felhasznalo1', 'felhasznalo1@gmail.com', '$2y$10$pceYqRGpg0mEQzQY9JOpdOg0dXLUK6KQQuTLGlwSlcdcEOiIlyfDi', '2023-11-14'),
('felhasznalo2', 'felhasznalo2@gmail.com', '$2y$10$8fh8bpTSSo9GLG8iW/nFQOWDeYyvKdLPg1CL.j5mlNFXeD1C.YUw6', '2023-11-14'),
('felhasznalo3', 'felhasznalo3@gmail.com', '$2y$10$b2OwFkUBfUVEGUR5nkqicu1iyDhyZN0xw3VMz0u7wpvXpI0IZeVWi', '2023-11-14'),
('felhasznalo4', 'felhasznalo4@gmail.com', '$2y$10$pNbNSozphbGiwsWIsdP0Xel4expk5nanwAqHXCv7cppjpkU3csdqa', '2023-11-14'),
('felhasznalo5', 'felhasznalo5@gmail.com', '$2y$10$c5JLjhdD5Tedxh1fpcEfR.ZKsgeomRkINY5zkeR9FeoFvPm2QMzpK', '2023-11-14');

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
(1, 'Participant1', '2000-10-10', 'Occupation1', 'Program1', NULL, 'felhasznalo1@gmail.com'),
(2, 'Participant2', '1980-05-20', 'Occupation2', 'Program2', NULL, 'felhasznalo1@gmail.com'),
(3, 'Participant3', '2001-10-20', 'Occupation3', 'Program3', NULL, 'felhasznalo1@gmail.com'),
(4, 'Participant4', '1979-05-11', 'Occupation4', 'Program4', NULL, 'felhasznalo1@gmail.com'),
(5, 'Participant5', '1979-05-18', 'Occupation5', 'Program5', NULL, 'felhasznalo1@gmail.com'),
(6, 'Participant6', '2002-05-24', 'Occupation6', 'Program6', NULL, 'felhasznalo1@gmail.com');

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
  MODIFY `Szavazat kod` int(6) NOT NULL AUTO_INCREMENT;

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