-- database/cedcine.sql
-- CedCine Movie Catalog and Rating Platform Database Schema

CREATE DATABASE IF NOT EXISTS `cedcine_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cedcine_db`;

-- --------------------------------------------------------
-- Table structure for table `movies`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `favorites`;
DROP TABLE IF EXISTS `movies`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `movies` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `genre` VARCHAR(100) NOT NULL,
  `release_year` INT NOT NULL,
  `rating` DECIMAL(3,1) NOT NULL DEFAULT 0.0,
  `director` VARCHAR(150) NOT NULL,
  `cast` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `poster_url` VARCHAR(500) NOT NULL,
  `backdrop_url` VARCHAR(500) NULL,
  `duration` VARCHAR(50) DEFAULT '2h 00m',
  `featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(150) NOT NULL,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `favorites`
-- --------------------------------------------------------
CREATE TABLE `favorites` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `movie_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favorites_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `unique_user_movie` UNIQUE (`user_id`, `movie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Seed Sample Movies
-- --------------------------------------------------------
INSERT INTO `movies` (`id`, `title`, `genre`, `release_year`, `rating`, `director`, `cast`, `description`, `poster_url`, `backdrop_url`, `duration`, `featured`, `created_at`) VALUES
(1, 'Interstellar', 'Sci-Fi / Adventure', 2014, 8.7, 'Christopher Nolan', 'Matthew McConaughey, Anne Hathaway, Jessica Chastain', 'When Earth becomes increasingly uninhabitable in the future, a farmer and ex-NASA pilot, Joseph Cooper, is tasked to pilot a spacecraft, along with a team of researchers, to find a new planet for humanity.', 'assets/images/posters/interstellar_poster.jpg', 'assets/images/posters/interstellar_backdrop.jpg', '2h 49m', 1, NOW()),
(2, 'Inception', 'Sci-Fi / Action', 2010, 8.8, 'Christopher Nolan', 'Leonardo DiCaprio, Joseph Gordon-Levitt, Elliot Page', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O., but his tragic past may doom the project.', 'assets/images/posters/inception_poster.jpg', 'assets/images/posters/inception_backdrop.jpg', '2h 28m', 0, NOW()),
(3, 'The Dark Knight', 'Action / Crime', 2008, 9.0, 'Christopher Nolan', 'Christian Bale, Heath Ledger, Aaron Eckhart', 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.', 'assets/images/posters/dark_knight_poster.jpg', 'assets/images/posters/dark_knight_backdrop.jpg', '2h 32m', 0, NOW()),
(4, 'Blade Runner 2049', 'Sci-Fi / Mystery', 2017, 8.0, 'Denis Villeneuve', 'Ryan Gosling, Harrison Ford, Ana de Armas', 'Young Blade Runner K\'s discovery of a long-buried secret leads him to track down former Blade Runner Rick Deckard, who has been missing for thirty years.', 'assets/images/posters/blade_runner_2049_poster.jpg', 'assets/images/posters/blade_runner_2049_backdrop.jpg', '2h 44m', 0, NOW()),
(5, 'Spirited Away', 'Animation / Fantasy', 2001, 8.6, 'Hayao Miyazaki', 'Rumi Hiiragi, Miyu Irino, Mari Natsuki', 'During her family\'s move to the suburbs, a sullen 10-year-old girl wanders into a world ruled by gods, witches, and spirits, where humans are changed into beasts.', 'assets/images/posters/spirited_away_poster.jpg', 'assets/images/posters/spirited_away_backdrop.jpg', '2h 05m', 0, NOW()),
(6, 'Pulp Fiction', 'Crime / Drama', 1994, 8.9, 'Quentin Tarantino', 'John Travolta, Uma Thurman, Samuel L. Jackson', 'The lives of two mob hitmen, a boxer, a gangster and his wife, and a pair of diner bandits intertwine in four tales of violence and redemption.', 'assets/images/posters/pulp_fiction_poster.jpg', 'assets/images/posters/pulp_fiction_backdrop.jpg', '2h 34m', 0, NOW()),
(7, 'Dune: Part Two', 'Sci-Fi / Action', 2024, 8.5, 'Denis Villeneuve', 'Timothée Chalamet, Zendaya, Rebecca Ferguson', 'Paul Atreides unites with Chani and the Fremen while seeking revenge against the conspirators who destroyed his family.', 'assets/images/posters/dune_2_poster.jpg', 'assets/images/posters/dune_2_backdrop.jpg', '2h 46m', 0, NOW()),
(8, 'Parasite', 'Drama / Thriller', 2019, 8.5, 'Bong Joon Ho', 'Song Kang-ho, Lee Sun-kyun, Cho Yeo-jeong', 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan.', 'assets/images/posters/parasite_poster.jpg', 'assets/images/posters/parasite_backdrop.jpg', '2h 12m', 0, NOW());
