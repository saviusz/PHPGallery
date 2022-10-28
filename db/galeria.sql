-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql_db
-- Czas generowania: 28 Paź 2022, 12:30
-- Wersja serwera: 8.0.30
-- Wersja PHP: 8.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `example`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `albums`
--

CREATE TABLE `albums` (
  `id` int NOT NULL,
  `title` varchar(100) COLLATE utf8mb3_polish_ci NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `authorId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;

--
-- Zrzut danych tabeli `albums`
--

INSERT INTO `albums` (`id`, `title`, `createdAt`, `authorId`) VALUES
(1, 'Oto tytył', '2022-10-26 09:20:28', 1),
(2, 'Tytuły dwa', '2022-10-26 09:20:31', 2),
(9, 'ALbum 3', '2022-10-27 12:31:29', 1),
(10, 'Kolejny album', '2022-10-27 15:48:47', 1),
(11, 'Klka kolejnych', '2022-10-27 23:51:52', 1),
(12, 'I jeszcze', '2022-10-27 23:59:57', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `photos`
--

CREATE TABLE `photos` (
  `id` int NOT NULL,
  `description` varchar(255) COLLATE utf8mb3_polish_ci NOT NULL,
  `albumId` int NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isAccepted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;

--
-- Zrzut danych tabeli `photos`
--

INSERT INTO `photos` (`id`, `description`, `albumId`, `createdAt`, `isAccepted`) VALUES
(1, 'Opis', 1, '2022-10-26 09:21:49', 1),
(2, 'Opis', 2, '2022-10-26 09:21:49', 1),
(3, 'Opis', 9, '2022-10-26 09:21:49', 1),
(4, 'Opis', 10, '2022-10-26 09:21:49', 1),
(5, 'Opis', 12, '2022-10-26 09:21:49', 1),
(6, 'Opis', 11, '2022-10-26 09:21:49', 1),
(7, 'Opis', 9, '2022-10-26 09:21:49', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `photos_comments`
--

CREATE TABLE `photos_comments` (
  `id` int NOT NULL,
  `photoId` int NOT NULL,
  `authorId` int DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text COLLATE utf8mb3_polish_ci NOT NULL,
  `isAccepted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `photos_ratings`
--

CREATE TABLE `photos_ratings` (
  `photoId` int NOT NULL,
  `userId` int NOT NULL,
  `rating` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_polish_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_polish_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_polish_ci NOT NULL,
  `registered_at` date NOT NULL,
  `role` enum('user','moderator','admin','') CHARACTER SET utf8mb3 COLLATE utf8mb3_polish_ci NOT NULL DEFAULT 'user',
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci ROW_FORMAT=COMPRESSED;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `registered_at`, `role`, `active`) VALUES
(1, 'SergioRambo', '2af9b1ba42dc5eb01743e6b3759b6e4b', 'serweryn123@gmail.com', '2022-10-25', 'user', 1),
(2, 'MalaMalpa', '2af9b1ba42dc5eb01743e6b3759b6e4b', 'test@example.com', '2022-10-26', 'user', 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photos_ibfk_1` (`albumId`);

--
-- Indeksy dla tabeli `photos_comments`
--
ALTER TABLE `photos_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photoId` (`photoId`),
  ADD KEY `photos_comments_ibfk_2` (`authorId`);

--
-- Indeksy dla tabeli `photos_ratings`
--
ALTER TABLE `photos_ratings`
  ADD PRIMARY KEY (`photoId`,`userId`),
  ADD KEY `userId` (`userId`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `albums`
--
ALTER TABLE `albums`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT dla tabeli `photos_comments`
--
ALTER TABLE `photos_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`albumId`) REFERENCES `albums` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `photos_comments`
--
ALTER TABLE `photos_comments`
  ADD CONSTRAINT `photos_comments_ibfk_1` FOREIGN KEY (`photoId`) REFERENCES `photos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `photos_comments_ibfk_2` FOREIGN KEY (`authorId`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `photos_ratings`
--
ALTER TABLE `photos_ratings`
  ADD CONSTRAINT `photos_ratings_ibfk_1` FOREIGN KEY (`photoId`) REFERENCES `photos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `photos_ratings_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
