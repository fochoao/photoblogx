<?php
	require_once('login.php');
	require_once('sanitize.php');
	if (!empty($_POST['get_values'])) {
    		$user_id = $logged_user_id;
		$user_query = $db_connection->prepare('SELECT * FROM photoblog_user WHERE user_id = ?;');
		$user_query->execute(array($user_id));
		$user_data = $user_query->fetch(PDO::FETCH_ASSOC);
		$admin = $user_data["user_mail"];
		$biography = $user_data["user_biography"];
		$user_timezone = $user_data["user_timezone"];
		$photoblog_title = $user_data["user_photoblog_title"];
		$photoblog_description = $user_data["user_photoblog_description"];
		$photoblog_keywords = $user_data["user_photoblog_keywords"];
		$json = array("settings"=>array("mail"=>$admin,"biography"=>$biography,"title"=>$photoblog_title,"description"=>$photoblog_description,"keywords"=>$photoblog_keywords,"timezone"=>$user_timezone,"modified"=>"yes"));
		print json_encode($json);
	}
	if (!empty($_POST['change_password'])) {
    		$user_id = $logged_user_id;
		$new_password = sanitize($_POST['change_password']);
		$new_password = hash("whirlpool", $new_password);
		$user_query = $db_connection->prepare('UPDATE photoblog_user SET user_password = ? WHERE user_id = ?;');
		$user_query->execute(array($new_password,$user_id));
		$mail_query = $db_connection->prepare('SELECT user_mail FROM photoblog_user WHERE user_id = ?;');
		$mail_query->execute(array($user_id));
		$user_data = $mail_query->fetch(PDO::FETCH_ASSOC);
		$user_mail = $user_data["user_mail"];
		$json = array("password"=>array("mail"=>$user_mail,"modified"=>"yes"));
		print json_encode($json);
	}
	if (!empty($_POST['change_biography'])) {
		$user_id = $logged_user_id;
    		$biography_post = $_POST['change_biography'];
		$user_query = $db_connection->prepare('UPDATE photoblog_user SET user_biography = ? WHERE user_id = ?;');
		$user_query->execute(array($biography_post,$user_id));
		$json = array("biography"=>array("modified"=>"yes"));
		print json_encode($json);
	}
	if (!empty($_POST['change_title'])) {
    		$user_id = $logged_user_id;
		$title = sanitize($_POST['change_title']);
		$user_query = $db_connection->prepare('UPDATE photoblog_user SET user_photoblog_title = ? WHERE user_id = ?;');
		$user_query->execute(array($title,$user_id));
		$json = array("title"=>array("modified"=>"yes"));
		print json_encode($json);
	}
	if (!empty($_POST['change_description'])) {
    		$user_id = $logged_user_id;
		$description = sanitize($_POST['change_description']);
		$user_query = $db_connection->prepare('UPDATE photoblog_user SET user_photoblog_description = ? WHERE user_id = ?;');
		$user_query->execute(array($description,$user_id));
		$json = array("description"=>array("modified"=>"yes"));
		print json_encode($json);
	}
	if (!empty($_POST['change_keywords'])) {
    		$user_id = $logged_user_id;
		$keywords = sanitize($_POST['change_keywords']);
		$user_query = $db_connection->prepare('UPDATE photoblog_user SET user_photoblog_keywords = ? WHERE user_id = ?;');
		$user_query->execute(array($keywords,$user_id));
		$json = array("keywords"=>array("modified"=>"yes"));
		print json_encode($json);
	}
	if (!empty($_POST['change_timezone'])) {
    		$user_id = $logged_user_id;
		$timezone_value = sanitize($_POST['change_timezone']);
		$user_query = $db_connection->prepare('UPDATE photoblog_user SET user_timezone = ? WHERE user_id = ?;');
		$user_query->execute(array($timezone_value,$user_id));
		$json = array("timezone"=>array("value"=>$timezone_value,"modified"=>"yes"));
		print json_encode($json);
	}
?>
