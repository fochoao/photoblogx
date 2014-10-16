<?php
    if (!empty($_POST['host_db']) && !empty($_POST['username_db']) && !empty($_POST['password_db']) && !empty($_POST['photo_mail']) && !empty($_POST['photo_password']) && !empty($_POST['photo_password_check']) && !empty($_POST['photoblog_title']) && !empty($_POST['photoblog_description']) && !empty($_POST['photoblog_keywords'])) {
    	require_once('pbadmin/sanitize.php');
		$db_hostname = sanitize($_POST['host_db']);
		$db_username = sanitize($_POST['username_db']);
		$db_password = sanitize($_POST['password_db']);
		$db_name_create = sanitize($_POST['db_namecreate']);
		$db_name = sanitize($_POST['name_db']);
		$photoblog_mail = sanitize($_POST['photo_mail']);
		$photoblog_password = sanitize($_POST['photo_password']);
		$photoblog_password_check = sanitize($_POST['photo_password_check']);
		$photoblog_timezone = sanitize($_POST['photo_timezone']);
		$photoblog_title = sanitize($_POST['photoblog_title']);
		$photoblog_description = sanitize($_POST['photoblog_description']);
		$photoblog_keywords = sanitize($_POST['photoblog_keywords']);
		if ($photoblog_password == $photoblog_password_check) {
			if (!empty($db_name)) {
				$db_dsn_host = "mysql:host=$db_hostname;dbname=$db_name";
			} else if (!empty($db_name_create)) {
				$create_db = $db_name_create;
				$db_dsn_host = "mysql:host=$db_hostname;";
			} 
			$db_options = array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);
			try {
				$db_connection = new PDO($db_dsn_host, $db_username, $db_password, $db_options);
			} catch (PDOException $db_error) {
				return $db_error->getMessage(); 
			}
			if (!isset($db_error)) {
				if (!empty($create_db)) {
					$create_db_query = $db_connection->prepare('CREATE DATABASE `'.$create_db.'` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;');
					$create_db_query->execute();
					$select_db_query = $db_connection->prepare('USE `'.$create_db.'`;');
					$select_db_query->execute();
					$create_user_table = $db_connection->prepare('CREATE TABLE `photoblog_user` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_user_table->execute();
					$create_photo_table = $db_connection->prepare('CREATE TABLE `photoblog_photo` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_file` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `photo_name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `photo_description` longtext COLLATE utf8_unicode_ci,
  `photo_date` date NOT NULL,
  `photo_time` time NOT NULL,
  `photo_tags` longtext COLLATE utf8_unicode_ci,
  `photo_user` int(11) NOT NULL,
  PRIMARY KEY (`photo_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_photo_table->execute();
					$create_comments_table = $db_connection->prepare('CREATE TABLE `photoblog_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `comment_email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `comment_photo_id` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_photo_fk_idx` (`comment_photo_id`),
  CONSTRAINT `comment_photo_fk` FOREIGN KEY (`comment_photo_id`) REFERENCES `photoblog_photo` (`photo_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_comments_table->execute();
					$create_category_table = $db_connection->prepare('CREATE TABLE `photoblog_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `category_description` longtext COLLATE utf8_unicode_ci,
  `category_user` int(11) NOT NULL,
  PRIMARY KEY (`category_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_category_table->execute();
					$create_categories_table = $db_connection->prepare('CREATE TABLE `photoblog_categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_photo_id` int(11) NOT NULL,
  `categories_category_id` int(11) NOT NULL,
  PRIMARY KEY (`categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_categories_table->execute();
					$delete_users_query = $db_connection->prepare('SELECT * FROM photoblog_user;');
					$delete_users_query->execute();
					$delete_users = $delete_users_query->fetchAll(PDO::FETCH_ASSOC);
					foreach ($delete_users as $delete) {
						$check_id = $delete['user_id'];
						if (!empty($check_id)) {
							$drop_user_query = $db_connection->prepare('DELETE FROM `photoblog_user` WHERE `user_id` = ?;');
							$drop_user_query->execute(array($check_id));
						}
					}
					$photoblog_password_hashed = hash('whirlpool', $photoblog_password);
					$insert_photoblog_user = $db_connection->prepare('INSERT INTO `photoblog_user` (user_mail, user_password, user_temporal, user_timezone, user_transient, user_photoblog_title, user_photoblog_description, user_photoblog_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
					$insert_photoblog_user->execute(array($photoblog_mail,$photoblog_password_hashed,'0',$photoblog_timezone,'0',$photoblog_title,$photoblog_description,$photoblog_keywords));
					$admin_chmod = 0755;
					$admin_dir = './pbadmin/';
					$thumb_dir = './thumbnails/';
					if (!is_dir($admin_dir) || !is_dir($thumb_dir)) {
						chmod($admin_dir,$admin_chmod);
						mkdir($thumb_dir, 0755);
						chmod($thumb_dir, 0755);
					}
					$config = fopen('pbadmin/config.php', 'w');
        			fwrite($config, '<?php
	$db_hostname = '."'".$db_hostname."'".';
	$db_name = '."'".$create_db."'".';
	$db_dsn_host = '.'"mysql:host=$db_hostname;dbname=$db_name";'.'
	$db_username = '."'".$db_username."'".';
	$db_password = '."'".$db_password."'".';
	$db_options = array(
		PDO::MYSQL_ATTR_INIT_COMMAND => '."'SET NAMES utf8'".',
	);
	try {
		$db_connection = new PDO($db_dsn_host, $db_username, $db_password, $db_options);
	} catch (PDOException $db_error) {
		return $db_error->getMessage(); 
	}
?>');
					$config_file = 'pbadmin/config.php';
					$config_chmod = 0644;
					if (file_exists($config_file)) {
						chmod($config_file,$config_chmod);
						$json = array("photoblog"=>array("succeed"=>"yes","config"=>"created","user"=>$photoblog_mail));
						print json_encode($json);
					}
				} else {
					$create_user_table = $db_connection->prepare('CREATE TABLE `photoblog_user` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_user_table->execute();
					$create_photo_table = $db_connection->prepare('CREATE TABLE `photoblog_photo` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_file` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `photo_name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `photo_description` longtext COLLATE utf8_unicode_ci,
  `photo_date` date NOT NULL,
  `photo_time` time NOT NULL,
  `photo_tags` longtext COLLATE utf8_unicode_ci,
  `photo_user` int(11) NOT NULL,
  PRIMARY KEY (`photo_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_photo_table->execute();
					$create_comments_table = $db_connection->prepare('CREATE TABLE `photoblog_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `comment_email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `comment_photo_id` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_photo_fk_idx` (`comment_photo_id`),
  CONSTRAINT `comment_photo_fk` FOREIGN KEY (`comment_photo_id`) REFERENCES `photoblog_photo` (`photo_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_comments_table->execute();
					$create_category_table = $db_connection->prepare('CREATE TABLE `photoblog_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `category_description` longtext COLLATE utf8_unicode_ci,
  `category_user` int(11) NOT NULL,
  PRIMARY KEY (`category_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_category_table->execute();
					$create_categories_table = $db_connection->prepare('CREATE TABLE `photoblog_categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_photo_id` int(11) NOT NULL,
  `categories_category_id` int(11) NOT NULL,
  PRIMARY KEY (`categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
					$create_categories_table->execute();
					$delete_users_query = $db_connection->prepare('SELECT * FROM photoblog_user;');
					$delete_users_query->execute();
					$delete_users = $delete_users_query->fetchAll(PDO::FETCH_ASSOC);
					foreach ($delete_users as $delete) {
						$check_id = $delete['user_id'];
						if (!empty($check_id)) {
							$drop_user_query = $db_connection->prepare('DELETE FROM `photoblog_user` WHERE `user_id` = ?;');
							$drop_user_query->execute(array($check_id));
						}
					}
					$photoblog_password_hashed = hash('whirlpool', $photoblog_password);
					$insert_photoblog_user = $db_connection->prepare('INSERT INTO `photoblog_user` (user_mail, user_password, user_temporal, user_timezone, user_transient, user_photoblog_title, user_photoblog_description, user_photoblog_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
					$insert_photoblog_user->execute(array($photoblog_mail,$photoblog_password_hashed,'0',$photoblog_timezone,'0',$photoblog_title,$photoblog_description,$photoblog_keywords));
					$config = fopen('pbadmin/config.php', 'w');
        			fwrite($config, '<?php
	$db_hostname = '."'".$db_hostname."'".';
	$db_name = '."'".$db_name."'".';
	$db_dsn_host = '.'"mysql:host=$db_hostname;dbname=$db_name";'.'
	$db_username = '."'".$db_username."'".';
	$db_password = '."'".$db_password."'".';
	$db_options = array(
		PDO::MYSQL_ATTR_INIT_COMMAND => '."'SET NAMES utf8'".',
	);
	try {
		$db_connection = new PDO($db_dsn_host, $db_username, $db_password, $db_options);
	} catch (PDOException $db_error) {
		return $db_error->getMessage(); 
	}
?>');
        			fclose($config);
					$config_file = 'pbadmin/config.php';
					if (file_exists($config_file)) {
						$config_chmod = 0644;
						chmod($config_file,$config_chmod);
						$json = array("photoblog"=>array("succeed"=>"yes","config"=>"created","user"=>$photoblog_mail));
						print json_encode($json);
					}
				}
			} else {
				$json = array("photoblog"=>array("succeed"=>"no"));
				print json_encode($json);
			}
		} else {
			$json = array("photoblog"=>array("succeed"=>"no"));
			print json_encode($json);
		}
	} else { 
		$json = array("photoblog"=>array("succeed"=>"Either the password verification was wrong, or You didn't fill the necessary fields."));
		print json_encode($json);
	}
?>
