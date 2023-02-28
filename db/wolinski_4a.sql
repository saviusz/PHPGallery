-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 28 Lut 2023, 18:47
-- Wersja serwera: 10.4.20-MariaDB
-- Wersja PHP: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `wolinski_4a`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `albums`
--

CREATE TABLE `albums` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `authorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `albums`
--

INSERT INTO `albums` (`id`, `title`, `createdAt`, `authorId`) VALUES
(1, 'marchwie', '2022-10-30 10:36:20', 1),
(2, 'kwiatki', '2022-10-30 15:36:24', 1),
(3, 'koty', '2022-10-31 09:21:48', 1),
(4, 'psy', '2022-10-31 09:22:03', 1),
(5, 'warszawa', '2022-10-31 09:23:08', 1),
(6, 'krakow', '2022-10-31 09:23:20', 1),
(7, 'gdansk', '2022-10-31 09:23:44', 1),
(8, 'wakacje', '2022-10-31 09:23:53', 1),
(9, 'zima', '2022-10-31 09:24:52', 1),
(10, 'wiosna', '2022-10-31 09:25:02', 1),
(11, 'lato', '2022-10-31 09:25:08', 1),
(12, 'jesien', '2022-10-31 09:25:14', 1),
(13, 'mapy', '2022-10-31 09:25:20', 1),
(14, 'memy', '2022-10-31 09:26:42', 1),
(15, 'szkola', '2022-10-31 09:27:00', 1),
(16, 'dom', '2022-10-31 09:27:15', 1),
(17, 'dziwne rzeczy w ikei', '2022-10-31 09:27:27', 1),
(18, 'rybki', '2022-10-31 09:27:46', 1),
(19, 'koncert', '2022-10-31 09:28:46', 1),
(20, 'alfa', '2022-10-31 09:28:56', 1),
(21, 'omega', '2022-10-31 09:29:06', 1),
(22, 'Segragacja', '2022-11-03 20:38:36', 2),
(23, 'Przytulaski', '2022-11-03 20:39:06', 2),
(24, 'Marchew', '2022-11-03 20:39:24', 2),
(25, 'SexyMarchew', '2022-11-03 20:39:38', 2),
(26, 'Dużo marchwii', '2022-11-03 20:39:56', 2),
(27, 'He heh, stab stab', '2022-11-03 20:40:19', 2),
(28, 'Hot marchew w Twojej okolicy', '2022-11-03 20:40:44', 2),
(29, 'Gdzie byłeś??? Hmm?', '2022-11-03 20:41:06', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `albumId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `isAccepted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `photos`
--

INSERT INTO `photos` (`id`, `description`, `albumId`, `createdAt`, `isAccepted`) VALUES
(1, 'marchew pospolita', 1, '2022-10-30 10:37:01', 1),
(2, 'chryzantemy', 2, '2022-10-31 09:37:43', 1),
(3, 'jakis kot', 3, '2022-10-31 09:38:27', 1),
(4, 'beagle', 4, '2022-10-31 09:39:14', 1),
(5, 'panorama', 5, '2022-10-31 09:39:29', 1),
(6, 'sukiennice', 6, '2022-10-31 09:39:43', 1),
(7, 'ulica', 7, '2022-10-31 09:39:57', 1),
(8, 'bad kreuznach', 8, '2022-10-31 09:54:20', 1),
(9, 'stoki', 9, '2022-10-31 09:54:50', 1),
(10, 'drzewka', 10, '2022-10-31 09:55:02', 1),
(11, 'łudka', 11, '2022-10-31 09:55:16', 1),
(12, 'las', 12, '2022-10-31 09:55:28', 1),
(13, 'mapa', 13, '2022-10-31 09:55:39', 1),
(14, 'dziwny pan ze stocka', 14, '2022-10-31 09:55:46', 1),
(15, 'szkola', 15, '2022-10-31 09:56:02', 1),
(16, 'jakis domek', 16, '2022-10-31 09:56:12', 1),
(17, 'ikea', 17, '2022-10-31 09:56:25', 1),
(18, 'welonek', 18, '2022-10-31 09:56:35', 1),
(19, 'gorgoroth \\m/', 19, '2022-10-31 09:56:51', 1),
(20, 'alpha', 20, '2022-10-31 09:57:19', 1),
(21, 'blake', 21, '2022-10-31 09:57:30', 1),
(22, 'Segregacja Marchwi', 22, '2022-11-03 20:43:03', 1),
(23, 'Cute marchew', 23, '2022-11-03 20:43:03', 1),
(24, 'Marchewka', 24, '2022-11-03 20:44:15', 1),
(25, 'Uuuu... Ale hotówa', 25, '2022-11-03 20:44:15', 1),
(26, 'Tona marchwi', 26, '2022-11-03 20:45:03', 1),
(27, 'Zabójstwo marchwi', 27, '2022-11-03 20:45:03', 1),
(28, 'Marchew przy ekspresie', 28, '2022-11-03 20:45:59', 1),
(29, 'MarchewŻona', 29, '2022-11-03 20:45:59', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `photos_comments`
--

CREATE TABLE `photos_comments` (
  `id` int(11) NOT NULL,
  `photoId` int(11) NOT NULL,
  `authorId` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `content` text COLLATE utf8_polish_ci NOT NULL,
  `isAccepted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `photos_ratings`
--

CREATE TABLE `photos_ratings` (
  `photoId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `registered_at` date NOT NULL,
  `role` enum('user','moderator','admin','') COLLATE utf8_polish_ci NOT NULL DEFAULT 'user',
  `active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci ROW_FORMAT=COMPRESSED;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `registered_at`, `role`, `active`) VALUES
(1, 'RealTests', '2af9b1ba42dc5eb01743e6b3759b6e4b', 'sigma@gmail.com', '2022-10-25', 'admin', 1),
(2, 'JustJoking', '2af9b1ba42dc5eb01743e6b3759b6e4b', 'test@example.com', '2022-10-26', 'user', 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT dla tabeli `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT dla tabeli `photos_comments`
--
ALTER TABLE `photos_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`albumId`) REFERENCES `albums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `photos_comments`
--
ALTER TABLE `photos_comments`
  ADD CONSTRAINT `photos_comments_ibfk_1` FOREIGN KEY (`photoId`) REFERENCES `photos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
