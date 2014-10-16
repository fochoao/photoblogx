<?php
	require_once('config.php');
    $query_first_photo = $db_connection->prepare('SELECT photo_user FROM photoblog_photo ORDER BY photo_id ASC LIMIT 1;');
	$query_first_photo->execute();
    $query_photo_user = $query_first_photo->fetch(PDO::FETCH_ASSOC);
	$photo_user = $query_photo_user['photo_user'];
    $site_settings_query = $db_connection->prepare('SELECT user_photoblog_title, user_photoblog_keywords, user_photoblog_description FROM photoblog_user WHERE user_id = ?;');
	$site_settings_query->execute(array($photo_user));
	$site_settings = $site_settings_query->fetch(PDO::FETCH_ASSOC);
	$photoblog_title = $site_settings['user_photoblog_title'];
	$photoblog_description = $site_settings['user_photoblog_description'];
	$photoblog_keywords = $site_settings['user_photoblog_keywords'];
	print '<meta name="description" content="'.$photoblog_description.'" />'."\n";
	print '<meta name="keywords" content="'.$photoblog_keywords.'" />'."\n";
	print "\n<title>$photoblog_title</title>\n";
?>