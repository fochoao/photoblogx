<?php
	require_once('pbadmin/config.php');
    $query_first_photo = $db_connection->prepare('SELECT photo_user FROM photoblog_photo ORDER BY photo_id ASC LIMIT 1;');
	$query_first_photo->execute();
    $query_photo_user = $query_first_photo->fetch(PDO::FETCH_ASSOC);
	$photo_user = $query_photo_user['photo_user'];
	$biography_query = $db_connection->prepare('SELECT user_biography FROM photoblog_user WHERE user_id = ?;');
	$biography_query->execute(array($photo_user));
	$biography = $biography_query->fetch(PDO::FETCH_ASSOC);
	$user_biography = $biography['user_biography'];
	print $user_biography;
?>