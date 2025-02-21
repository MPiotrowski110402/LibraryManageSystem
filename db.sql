-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Wersja serwera:               11.6.2-MariaDB - mariadb.org binary distribution
-- Serwer OS:                    Win64
-- HeidiSQL Wersja:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Zrzut struktury bazy danych librarysystem
CREATE DATABASE IF NOT EXISTS `librarysystem` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `librarysystem`;

-- Zrzut struktury tabela librarysystem.books
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `available_copies` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli librarysystem.books: ~3 rows (około)
INSERT INTO `books` (`id`, `title`, `author`, `genre`, `description`, `published_date`, `available_copies`) VALUES
	(1, 'Wielki Gatsby', 'F. Scott Fitzgerald', 'Fikcja', 'Opowieść o miłości, marzeniach i zdradzie.', '1925-04-10', 6),
	(2, '1984', 'George Orwell', 'Dystopia', 'Książka opisująca totalitarny reżim.', '1949-06-08', 5),
	(3, 'Zbrodnia i kara', 'Fyodor Dostoevsky', 'Klasyka', 'a', '1866-01-01', 3);

-- Zrzut struktury tabela librarysystem.borrowings
CREATE TABLE IF NOT EXISTS `borrowings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` timestamp NULL DEFAULT current_timestamp(),
  `return_date` timestamp NULL DEFAULT NULL,
  `due_date` timestamp NOT NULL,
  `returned` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `borrowings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `borrowings_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli librarysystem.borrowings: ~9 rows (około)
INSERT INTO `borrowings` (`id`, `user_id`, `book_id`, `borrow_date`, `return_date`, `due_date`, `returned`) VALUES
	(13, 5, 1, '2025-02-19 23:00:00', '2025-02-20 15:41:17', '2025-03-08 23:00:00', 1),
	(14, 5, 1, '2025-02-19 23:00:00', '2025-02-20 15:41:18', '2025-03-06 23:00:00', 1),
	(15, 5, 1, '2025-02-19 23:00:00', '2025-02-20 15:42:21', '2025-02-27 23:00:00', 1),
	(16, 5, 1, '2025-02-19 23:00:00', '2025-02-20 15:42:18', '2025-03-06 23:00:00', 1),
	(17, 5, 1, '2025-02-19 23:00:00', '2025-02-20 15:42:20', '2025-03-06 23:00:00', 1),
	(18, 5, 1, '2025-02-19 23:00:00', '2025-02-20 15:42:22', '2025-03-06 23:00:00', 1),
	(19, 5, 1, '2025-01-19 23:00:00', '2025-02-20 16:35:37', '2025-02-17 23:00:00', 1),
	(20, 5, 2, '2025-02-19 23:00:00', '2025-02-20 16:35:33', '2025-02-18 23:00:00', 1),
	(21, 5, 3, '2025-02-19 23:00:00', '2025-02-20 16:35:36', '2025-02-20 23:00:00', 1);

-- Zrzut struktury tabela librarysystem.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_date` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('sent','read','unread') DEFAULT 'unread',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli librarysystem.notifications: ~3 rows (około)
INSERT INTO `notifications` (`id`, `user_id`, `book_id`, `message`, `sent_date`, `status`) VALUES
	(1, 1, 1, 'Termin zwrotu książki "Wielki Gatsby" zbliża się! Wróć do biblioteki przed 10 marca.', '2025-02-19 13:37:07', 'unread'),
	(2, 2, 2, 'Termin zwrotu książki "1984" zbliża się! Wróć do biblioteki przed 28 lutego.', '2025-02-19 13:37:07', 'unread'),
	(3, 3, 3, 'Termin zwrotu książki "Zbrodnia i kara" zbliża się! Wróć do biblioteki przed 15 kwietnia.', '2025-02-19 13:37:07', 'unread');

-- Zrzut struktury tabela librarysystem.reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `reservation_date` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'reserved',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli librarysystem.reservations: ~0 rows (około)

-- Zrzut struktury tabela librarysystem.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_text` text DEFAULT NULL,
  `review_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli librarysystem.reviews: ~3 rows (około)
INSERT INTO `reviews` (`id`, `user_id`, `book_id`, `rating`, `review_text`, `review_date`) VALUES
	(1, 1, 1, 5, 'Świetna książka! Wciągająca fabuła, pełna emocji.', '2025-02-19 13:36:58'),
	(2, 2, 2, 4, 'Doskonała, ale zbyt pesymistyczna.', '2025-02-19 13:36:58'),
	(3, 1, 3, 3, 'Długie opisy, ale dobra książka do refleksji.', '2025-02-19 13:36:58');

-- Zrzut struktury tabela librarysystem.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli librarysystem.roles: ~2 rows (około)
INSERT INTO `roles` (`id`, `name`) VALUES
	(1, 'Administrator'),
	(2, 'Czytelnik');

-- Zrzut struktury tabela librarysystem.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `registration_date` timestamp NULL DEFAULT current_timestamp(),
  `cid` varchar(20) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `newsletter_announcer` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli librarysystem.users: ~5 rows (około)
INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role_id`, `registration_date`, `cid`, `birth_date`, `newsletter_announcer`) VALUES
	(1, 'Jan', 'Kowalski', 'jan.kowalski@example.com', 'password123', 2, '2025-02-19 13:36:11', '#111111', '2022-02-11', 0),
	(2, 'Anna', 'Nowak', 'anna.nowak@example.com', 'password123', 2, '2025-02-19 13:36:11', '#111112', '2025-02-19', 0),
	(3, 'Marek', 'Zieliński', 'marek.zielinski@example.com', 'adminpass', 1, '2025-02-19 13:36:11', '#111113', '2025-02-19', 0),
	(4, 'test', 'test', 'test@test', '1', 2, '2025-02-19 14:01:05', '#111114', '2025-02-19', 0),
	(5, 'Mateusz', 'Piotrowski', 'mp110402@gmail.com', '1', 1, '2025-02-19 15:21:14', '#583514', '2025-02-05', 1),
	(6, 'Krzysztof', 'Malisz', 'malisz@gmail.com', 'Malisz', 2, '2025-02-21 11:49:26', '#814751', '2025-01-28', 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
