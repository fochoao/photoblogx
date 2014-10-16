<?php
	require_once('pbadmin/config.php');
	require_once('pbadmin/sanitize.php');
	session_cache_limiter('private, must-revalidate');
	$cache_limit = session_cache_limiter();
	session_cache_expire(30);
	$cache_expire = session_cache_expire();
	session_start();
	if (!empty($_GET['comment_name'])) {
		$comment_id = sanitize($_GET['comment_id']);
		$comment_name = sanitize($_GET['comment_name']);
		$comment_mail = sanitize($_GET['comment_mail']);
		$comment_content = sanitize($_GET['comment_content']);
		$comment_captcha = sanitize($_GET['comment_captcha']);
		$session_captcha = $_SESSION["photoblog_captcha"];
		if ($comment_captcha == $session_captcha && is_numeric($session_captcha) && is_numeric($comment_captcha)) {
			if (empty($comment_mail) || $comment_mail == "") {
				$insert_comment = $db_connection->prepare('INSERT INTO photoblog_comments (comment_name, comment_content, comment_photo_id) VALUES (?, ?, ?);');
				$insert_comment->execute(array($comment_name,$comment_content,$comment_id));
			} else {
				$insert_comment = $db_connection->prepare('INSERT INTO photoblog_comments (comment_name, comment_email, comment_content, comment_photo_id) VALUES (?, ?, ?, ?);');
				$insert_comment->execute(array($comment_name,$comment_mail,$comment_content,$comment_id));
			}
			$count_comments_query = $db_connection->prepare('SELECT COUNT(comment_content) FROM photoblog_comments WHERE comment_photo_id = ?;');
			$count_comments_query->execute(array($comment_id));
			$count_comments = $count_comments_query->fetchColumn();
			$json = array("comment"=>array("name"=>$comment_name,"content"=>$comment_content,"number"=>$count_comments,"insert"=>"yes"));
			print json_encode($json);
		}
	}
	session_destroy();
?>