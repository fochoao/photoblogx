<?php
	require_once('config.php');
	require_once('sanitize.php');
	if ($_SERVER['SERVER_PORT'] == '80') {
	    	$link = 'http://'.$_SERVER['SERVER_NAME'].trim($_SERVER['PHP_SELF'],'sendtoken.php').'changepassword.php';
	    } else if  ($_SERVER['SERVER_PORT'] == '443') {
    		$link = 'https://'.$_SERVER['SERVER_NAME'].trim($_SERVER['PHP_SELF'],'sendtoken.php').'changepassword.php';
	}
	if (!empty($_POST['email'])) {
		$email = sanitize($_POST['email']);
		$check_existing_mail = $db_connection->prepare('SELECT user_id, user_mail, user_photoblog_title FROM photoblog_user WHERE user_mail = ?;');
		$check_existing_mail->execute(array($email));
		$check_mail = $check_existing_mail->fetchAll(PDO::FETCH_ASSOC);
		foreach($check_mail as $mail_db) {
			$user_mail = $mail_db['user_mail'];
			$user_id = $mail_db['user_id'];
			$user_photoblog = $mail_db['user_photoblog_title'];
		}
		if (!empty($user_mail)) {
			$to_hash = $user_id.$user_mail.mt_rand(100, 900);
			$recover_hash = hash('ripemd160', $to_hash);
			$transient_hash_query = $db_connection->prepare('UPDATE photoblog_user SET user_transient = ? WHERE user_id = ?;');
			$transient_hash_query->execute(array($recover_hash,$user_id));
			$title_mail = "$user_photoblog: Change password to Your Photoblog";
			$body_mail = '<p>Follow the next link to change Your password:</p><p><a href="'.$link.'?token='.$recover_hash.'" title="Change Password" alt="Change Password">Change Password of Your Photoblog.</a></p><br /><p>Remember Your login email is ';
			$body_mail .= $user_mail.'</p><p>'.$user_photoblog.'</p>';
			$date_today = date("y/m/d(D) g:i");
			$headers = "From: photoblogx@itdesarrollo.com\r\n";
			$headers .= "X-Mailer: PHP PhotoblogX Script\r\n";
			$headers .= "X-Priority: 3\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Importance: 3\r\n";
			$headers .= "X-MSMail-Priority: High\r\n";
			$headers .= "Content-type: text/html; charset=UTF-8\r\n";
			$headers .= "Date: ".$date_today."\r\n";
			$headers .= "Delivered-to: $user_mail";
			mail($user_mail, $title_mail, $body_mail, $headers);
			$json = array("password"=>array("token"=>"yes"));
			print json_encode($json);
		}
	} else if (!empty($_POST['token']) && strlen($_POST['token']) == 40 && !empty($_POST['password']) && !empty($_POST['password_verify'])) {
		$token = sanitize($_POST['token']);
		$password = sanitize($_POST['password']);
		$password_verify = sanitize($_POST['password_verify']);
		if ($session_captcha == $captcha && $password == $password_verify) {
			$query_token = $db_connection->prepare('SELECT user_id, user_mail FROM photoblog_user WHERE user_transient = ?;');
			$query_token->execute(array($token));
			$token_fetch = $query_token->fetch(PDO::FETCH_ASSOC);
			$user_mail = $token_fetch['user_mail'];
			$user_id = $token_fetch['user_id'];
			if (!empty($user_mail) && !empty($user_id) && is_numeric($user_id)) {
				$password_hashed = hash('whirlpool', $password);
				$password_hash_query = $db_connection->prepare('UPDATE photoblog_user SET user_password = ?, user_transient = ? WHERE user_id = ?;');
				$password_hash_query->execute(array($password_hashed,'0',$user_id));
				$json = array("password"=>array("changed"=>"yes"));
				print json_encode($json);
			}
		}
	}
?>
