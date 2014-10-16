<?php
	require_once('config.php');
    $query_first_photo = $db_connection->prepare('SELECT photo_user FROM photoblog_photo ORDER BY photo_id ASC LIMIT 1;');
	$query_first_photo->execute();
    $query_photo_user = $query_first_photo->fetch(PDO::FETCH_ASSOC);
	$photo_user = $query_photo_user['photo_user'];
    $site_settings_query = $db_connection->prepare('SELECT user_photoblog_title FROM photoblog_user WHERE user_id = ?;');
	$site_settings_query->execute(array($photo_user));
	$site_settings = $site_settings_query->fetch(PDO::FETCH_ASSOC);
	$photoblog_title = $site_settings['user_photoblog_title'];
	$capitalize_title = ucwords(strtolower($photoblog_title));
	print ' title="'.$capitalize_title.'" alt="'.$capitalize_title.'">'.$photoblog_title."</a>";
?>