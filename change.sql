ALTER TABLE `photoblog_user` ADD `user_timezone` VARCHAR(140) COLLATE utf8_unicode_ci NOT NULL AFTER `user_temporal`,
ADD `user_transient` VARCHAR(40) COLLATE utf8_unicode_ci NOT NULL AFTER `user_timezone;