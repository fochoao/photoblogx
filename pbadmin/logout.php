<?php
	require_once('login.php');
	$cache_limit = session_cache_limiter();
	session_cache_expire(30);
	$cache_expire = session_cache_expire();
	$temporal_key_session = $_SESSION["temporal_hash"];
	$temporal_key_query = $db_connection->prepare('SELECT user_temporal, user_mail FROM photoblog_user WHERE user_temporal = ?;');
	$temporal_key_query->execute(array($temporal_key_session));
	$temporal_data = $temporal_key_query->fetch(PDO::FETCH_ASSOC);
	if ($temporal_key_session == $temporal_data["user_temporal"]) {
		$user_mail = $temporal_data["user_mail"];
		$_SESSION["temporal_hash"] = array();
		$temporal_key = 0;
		$temporal_user_db = $db_connection->prepare('UPDATE photoblog_user SET user_temporal = ? WHERE user_mail = ?;');
		$temporal_user_db->execute(array($temporal_key,$user_mail));
	}
	session_destroy();
	exit(header('Location: index.php'));
?>