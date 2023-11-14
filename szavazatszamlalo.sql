-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Nov 14. 15:23
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
(1, 'Participant1', '2000-10-10', 'Occupation1', 'Program1', 40, 'felhasznalo1@gmail.com'),
(2, 'Participant2', '1980-05-20', 'Occupation2', 'Program2', 41, 'felhasznalo1@gmail.com'),
(3, 'Participant3', '2001-10-20', 'Occupation3', 'Program3', 42, 'felhasznalo1@gmail.com'),
(4, 'Participant4', '1979-05-11', 'Occupation4', 'Program4', 42, 'felhasznalo1@gmail.com'),
(5, 'Participant5', '1979-05-18', 'Occupation5', 'Program5', 43, 'felhasznalo1@gmail.com'),
(6, 'Participant6', '2002-05-24', 'Occupation6', 'Program6', 44, 'felhasznalo1@gmail.com'),
(7, 'Participant7', '1970-01-27', 'Occupation7', 'Program7', 43, 'felhasznalo1@gmail.com'),
(8, 'Participant8', '1995-04-20', 'Occupation8', 'Program8', 45, 'felhasznalo1@gmail.com'),
(9, 'Participant9', '1998-03-19', 'Occupation9', 'Program9', 45, 'felhasznalo1@gmail.com'),
(10, 'Participant10', '1986-08-07', 'Occupation10', 'Program10', 46, 'felhasznalo1@gmail.com'),
(11, 'Participant11', '1967-10-03', 'Occupation11', 'Program11', 47, 'felhasznalo1@gmail.com'),
(12, 'Participant12', '1984-03-06', 'Occupation12', 'Program12', 47, 'felhasznalo1@gmail.com'),
(13, 'Participant10', '1986-08-07', 'Occupation10', 'Program10', 46, 'felhasznalo2@gmail.com'),
(14, 'Participant13', '1975-07-13', 'Occupation13', 'Program13', 48, 'felhasznalo2@gmail.com'),
(15, 'Participant14', '1954-10-02', 'Occupation14', 'Program14', 48, 'felhasznalo2@gmail.com'),
(16, 'Participant15', '1992-02-04', 'Occupation15', 'Program15', 48, 'felhasznalo2@gmail.com'),
(17, 'Participant16', '2001-01-01', 'Occupation16', 'Program16', 49, 'felhasznalo2@gmail.com');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szavazas`
--

CREATE TABLE `szavazas` (
  `Megnevezes` varchar(20) NOT NULL,
  `Leiras` varchar(100) NOT NULL,
  `Jeloltek` text NOT NULL,
  `Indul` date NOT NULL,
  `Zarul` date NOT NULL,
  `Szavazas kod` int(6) NOT NULL,
  `Email` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `szavazas`
--

INSERT INTO `szavazas` (`Megnevezes`, `Leiras`, `Jeloltek`, `Indul`, `Zarul`, `Szavazas kod`, `Email`) VALUES
('Vote1', 'Vote1 description', 'Participant1', '2023-01-01', '2023-12-31', 40, 'felhasznalo1@gmail.com'),
('Vote2', 'Vote description', 'Participant2', '2023-05-24', '2024-05-24', 41, 'felhasznalo1@gmail.com'),
('Vote3', 'Vote3 description', 'Participant3,Participant4', '2023-04-16', '2024-04-16', 42, 'felhasznalo1@gmail.com'),
('Vote4', 'Vote4 description', 'Participant5,Participant7', '2023-11-14', '2023-11-16', 43, 'felhasznalo1@gmail.com'),
('Vote6', 'Vote6 description', 'Participant6', '2023-10-15', '2024-02-18', 44, 'felhasznalo1@gmail.com'),
('Vote7', 'Vote7 description', 'Participant8,Participant9', '2023-03-20', '2023-12-01', 45, 'felhasznalo1@gmail.com'),
('Vote5', 'Vote5 description', 'Participant10', '2023-06-20', '2023-12-01', 46, 'felhasznalo2@gmail.com'),
('Vote8', 'Vote8 description', 'Participant12,Participant11', '2023-05-10', '2023-11-30', 47, 'felhasznalo2@gmail.com'),
('Vote9', 'Vote9 description', 'Participant13,Participant14,Participant15', '2023-11-10', '2023-12-10', 48, 'felhasznalo2@gmail.com'),
('Vote10', 'Vote10 description', 'Participant16', '2023-09-04', '2024-02-03', 49, 'felhasznalo2@gmail.com');

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
('felhasznalo1', '43', 'Participant5', '2023-11-14', 4, 43, 'felhasznalo1@gmail.com'),
('felhasznalo1', '45', 'Participant8', '2023-11-14', 5, 45, 'felhasznalo1@gmail.com'),
('felhasznalo2', '40', 'Participant1', '2023-11-14', 6, 40, 'felhasznalo2@gmail.com'),
('felhasznalo2', '45', 'Participant9', '2023-11-14', 7, 45, 'felhasznalo2@gmail.com'),
('felhasznalo2', '41', 'Participant2', '2023-11-14', 8, 41, 'felhasznalo2@gmail.com'),
('felhasznalo2', '46', 'Participant10', '2023-11-14', 10, 46, 'felhasznalo2@gmail.com'),
('felhasznalo2', '48', 'Participant14', '2023-11-14', 11, 48, 'felhasznalo2@gmail.com'),
('felhasznalo2', '47', 'Participant12', '2023-11-14', 12, 47, 'felhasznalo2@gmail.com'),
('felhasznalo3', '40', 'Participant1', '2023-11-14', 13, 40, 'felhasznalo3@gmail.com'),
('felhasznalo3', '42', 'Participant4', '2023-11-14', 14, 42, 'felhasznalo3@gmail.com'),
('felhasznalo3', '45', 'Participant8', '2023-11-14', 15, 45, 'felhasznalo3@gmail.com'),
('felhasznalo3', '48', 'Participant14', '2023-11-14', 16, 48, 'felhasznalo3@gmail.com'),
('felhasznalo4', '40', 'Participant1', '2023-11-14', 17, 40, 'felhasznalo4@gmail.com'),
('felhasznalo4', '44', 'Participant6', '2023-11-14', 18, 44, 'felhasznalo4@gmail.com'),
('felhasznalo5', '40', 'Participant1', '2023-11-14', 19, 40, 'felhasznalo5@gmail.com'),
('felhasznalo5', '43', 'Participant5', '2023-11-14', 20, 43, 'felhasznalo5@gmail.com'),
('felhasznalo5', '45', 'Participant8', '2023-11-14', 21, 45, 'felhasznalo5@gmail.com'),
('felhasznalo5', '48', 'Participant13', '2023-11-14', 22, 48, 'felhasznalo5@gmail.com'),
('felhasznalo5', '41', 'Participant2', '2023-11-14', 23, 41, 'felhasznalo5@gmail.com'),
('felhasznalo5', '42', 'Participant3', '2023-11-14', 24, 42, 'felhasznalo5@gmail.com');

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
  MODIFY `Jelolt kod` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT a táblához `szavazas`
--
ALTER TABLE `szavazas`
  MODIFY `Szavazas kod` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT a táblához `szavazat`
--
ALTER TABLE `szavazat`
  MODIFY `Szavazat kod` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
