CREATE DATABASE `photoblog` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE `photoblog`;

CREATE TABLE `photoblog_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_mail` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `user_password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `user_temporal` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `user_timezone` varchar(140) COLLATE utf8_unicode_ci NOT NULL,

  `user_transient` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `user_biography` longtext COLLATE utf8_unicode_ci,
  `user_photoblog_title` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `user_photoblog_description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `user_photoblog_keywords` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `photoblog_photo` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_file` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `photo_name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `photo_description` longtext COLLATE utf8_unicode_ci,
  `photo_date` date NOT NULL,
  `photo_time` time NOT NULL,
  `photo_tags` longtext COLLATE utf8_unicode_ci,
  `photo_user` int(11) NOT NULL,
  PRIMARY KEY (`photo_id`),
  KEY `photo_user_fk_idx` (`photo_user`),
  CONSTRAINT `photo_user_fk` FOREIGN KEY (`photo_user`) REFERENCES `photoblog_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `photoblog_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `comment_email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `comment_photo_id` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_photo_fk_idx` (`comment_photo_id`),
  CONSTRAINT `comment_photo_fk` FOREIGN KEY (`comment_photo_id`) REFERENCES `photoblog_photo` (`photo_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `photoblog_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `category_description` longtext COLLATE utf8_unicode_ci,
  `category_user` int(11) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `category_user_fk_idx` (`category_user`),
  CONSTRAINT `category_user_fk` FOREIGN KEY (`category_user`) REFERENCES `photoblog_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `photoblog_categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_photo_id` int(11) NOT NULL,
  `categories_category_id` int(11) NOT NULL,
  PRIMARY KEY (`categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;