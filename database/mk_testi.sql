-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 24. avg 2015 ob 19.20
-- Različica strežnika: 5.6.17
-- Različica PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Zbirka podatkov: `mk_testi`
--

-- --------------------------------------------------------

--
-- Struktura tabele `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `correct` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `answers_question_id_foreign` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Odloži podatke za tabelo `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `title`, `correct`, `created_at`, `updated_at`) VALUES
(1, 1, 'coffee', 1, '2015-08-23 07:08:30', '2015-08-23 07:08:30'),
(2, 2, 'house', 1, '2015-08-23 07:08:30', '2015-08-23 07:08:30'),
(3, 3, 'dog', 1, '2015-08-23 07:08:30', '2015-08-23 07:08:30'),
(4, 4, 'family', 1, '2015-08-23 07:08:30', '2015-08-23 07:08:30'),
(5, 5, 'cat', 1, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(6, 5, 'dog', 0, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(7, 5, 'kangaroo', 0, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(8, 5, 'bird', 0, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(9, 6, 'dumpster', 0, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(10, 6, 'house', 1, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(11, 6, 'block of flats', 1, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(12, 6, 'devblog', 0, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(13, 7, 'coffee', 1, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(14, 8, 'dog', 1, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(15, 9, 'tree', 1, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(16, 9, 'trees', 1, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(17, 10, 'dog', 1, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(18, 10, 'camel', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(19, 10, 'cat', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(20, 11, 'mutilated baby', 1, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(21, 11, 'dog', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(22, 11, 'cat', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(23, 11, 'camel', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(24, 12, 'kangaroo', 1, '2015-08-23 09:19:16', '2015-08-23 09:19:16'),
(25, 13, 'house', 1, '2015-08-23 09:19:16', '2015-08-23 09:19:16'),
(26, 14, 'kitchen', 1, '2015-08-23 09:23:31', '2015-08-23 09:23:31'),
(27, 15, 'car', 1, '2015-08-23 09:23:31', '2015-08-23 09:23:31'),
(28, 16, 'weed', 1, '2015-08-23 09:23:31', '2015-08-23 09:23:31');

-- --------------------------------------------------------

--
-- Struktura tabele `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Odloži podatke za tabelo `books`
--

INSERT INTO `books` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'Book #1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabele `exams`
--

CREATE TABLE IF NOT EXISTS `exams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_limit` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `exams_book_id_foreign` (`book_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Odloži podatke za tabelo `exams`
--

INSERT INTO `exams` (`id`, `book_id`, `title`, `time_limit`, `created_at`, `updated_at`) VALUES
(2, 1, 'Exam #1', 0, '2015-08-23 07:08:29', '2015-08-23 07:08:29'),
(4, 1, 'Exam #2', 0, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(6, 1, 'Exam #3', 0, '2015-08-23 08:17:16', '2015-08-23 08:17:16'),
(7, 1, 'Exam #4', 0, '2015-08-23 09:19:16', '2015-08-23 09:19:16'),
(8, 1, 'Exam #5', 0, '2015-08-23 09:23:30', '2015-08-23 09:23:30');

-- --------------------------------------------------------

--
-- Struktura tabele `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Odloži podatke za tabelo `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2015_08_08_151308_create_books_table', 1),
('2015_08_08_151823_create_exams_table', 1),
('2015_08_08_151839_create_task_table', 1),
('2015_08_08_151840_create_question_table', 1),
('2015_08_08_152149_create_answer_table', 1);

-- --------------------------------------------------------

--
-- Struktura tabele `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_src` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `questions_task_id_foreign` (`task_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Odloži podatke za tabelo `questions`
--

INSERT INTO `questions` (`id`, `task_id`, `title`, `image_src`, `type`, `created_at`, `updated_at`) VALUES
(1, 1, '', 'assets/uploads/55d98d8e417d9.jpeg', 0, '2015-08-23 07:08:30', '2015-08-23 07:08:30'),
(2, 1, '', 'assets/uploads/55d98d8e66009.jpeg', 0, '2015-08-23 07:08:30', '2015-08-23 07:08:30'),
(3, 1, '', 'assets/uploads/55d98d8e7e460.jpeg', 0, '2015-08-23 07:08:30', '2015-08-23 07:08:30'),
(4, 1, '', 'assets/uploads/55d98d8e96aac.jpeg', 0, '2015-08-23 07:08:30', '2015-08-23 07:08:30'),
(5, 2, 'It has 4 legs and it barks', '', 0, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(6, 2, 'You can live in it', '', 0, '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(7, 3, '', '/assets/uploads/55d99dad198b0.jpeg', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(8, 3, '', '/assets/uploads/55d99dad284ae.jpeg', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(9, 3, '', '/assets/uploads/55d99dad453be.jpeg', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(10, 4, 'It has 4 legs and it barks', '', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(11, 4, 'It has 1 leg and 1 arm', '', 0, '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(12, 5, 'It has 2 legs and it jumps (o,r,g,k,a,o,n,a)', '', 1, '2015-08-23 09:19:16', '2015-08-23 09:19:16'),
(13, 5, 'You can live in it (u,h,s,o,e)', '', 1, '2015-08-23 09:19:16', '2015-08-23 09:19:16'),
(14, 6, 'Yesterday, I was so hungry, I went to the _______ and made myself something to eat.', '', 1, '2015-08-23 09:23:30', '2015-08-23 09:23:30'),
(15, 6, 'I was driving in my ___, then I hit a child and killed it.', '', 1, '2015-08-23 09:23:31', '2015-08-23 09:23:31'),
(16, 6, 'I smoked to much ____ yesterday, I was stoned up to the roof!', '', 1, '2015-08-23 09:23:31', '2015-08-23 09:23:31');

-- --------------------------------------------------------

--
-- Struktura tabele `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `exam_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `tasks_exam_id_foreign` (`exam_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Odloži podatke za tabelo `tasks`
--

INSERT INTO `tasks` (`id`, `exam_id`, `title`, `created_at`, `updated_at`) VALUES
(1, 2, 'Write the correct answers below images', '2015-08-23 07:08:30', '2015-08-23 07:08:30'),
(2, 4, 'Select the correct answers', '2015-08-23 07:33:21', '2015-08-23 07:33:21'),
(3, 6, 'Write the correct answers below images', '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(4, 6, 'Select the correct answers', '2015-08-23 08:17:17', '2015-08-23 08:17:17'),
(5, 6, 'Iz možnih črk sestavi manjkajočo besedo', '2015-08-23 09:19:16', '2015-08-23 09:19:16'),
(6, 6, 'Vpiši manjkajoče besede', '2015-08-23 09:23:30', '2015-08-23 09:23:30');

-- --------------------------------------------------------

--
-- Struktura tabele `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_type` int(11) NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Odloži podatke za tabelo `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `user_type`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 0, '$2y$10$qrhBCbzis0Y4xb6Y3UcwduHdbSKARy7.pEdi.80ssUvkyZu.4kOze', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Omejitve tabel za povzetek stanja
--

--
-- Omejitve za tabelo `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Omejitve za tabelo `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Omejitve za tabelo `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

--
-- Omejitve za tabelo `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
