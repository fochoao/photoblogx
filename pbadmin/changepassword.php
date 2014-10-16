<?php
	require_once('sanitize.php');
	session_cache_limiter('private, must-revalidate');
	$cache_limit = session_cache_limiter();
	session_cache_expire(30);
	$cache_expire = session_cache_expire();
	session_start();
	if (!empty($_GET['token']) && strlen($_GET['token']) == 40) {
		$current_token = sanitize($_GET['token']);
	} else {
		exit(header('Location: index.php'));
	}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="PhotoblogX" /><?php
			require_once('printhead.php');
		?><link href="../css/main.css" type="text/css" rel="stylesheet" />
		
		<script src="../js/jquery-1.9.1.js" type="text/javascript"></script>
		<script src="../js/tooltip.js" type="text/javascript"></script>
		<script type="text/javascript">
			function start() {
				$('body').fadeIn(1000);
			};
			$(document).ready(function (){
				$('body').hide();
				$('a[title],input[title],button[title]').Tooltip();
				$('input,button').focusin(function() {
					$(this).fadeTo(500, 0.90).css('background-color','#444');
				});
				$('input,button').focusout(function() {
					$(this).fadeTo(500, 0.40).css('background-color','#666');
				});
				$('input,button').focus(function() {
					$(this).fadeTo(500, 0.90).css('background-color','#444');
				});
				$(document).on("click", '#change_password', function(){
					var field_token = $('input[name="token_change"]').prop('value');
					var field_password = $('input[name="password"]').prop('value');
					var field_password_verify = $('input[name="password_verify"]').prop('value');
					var field_captcha = $('input[name="captcha"]').prop('value');
					$.ajax({
						url: $("form#change_password_form").attr('action'),
						type: $("form#change_password_form").attr('method'),
						data: { token: field_token, password: field_password, password_verify: field_password_verify, captcha: field_captcha },
						dataType: 'json',
						cache: false,
						success: function(change_pass) {
							if (change_pass.password.changed == "yes") {
								$("#password-change").html('<p>Password has been succesfully changed.</p>');
							}
						}
					});
				});
			});
		</script>
		
	</head>
	<body onload="start();">
			<div class="header" id="header-margin">
				<p><a href="index.php"<?php require_once('titleprint.php'); ?></p>
			</div>
			<div class="login" id="login-margin">
				<p>Lost Password</p>
				<br />
				<form action="sendtoken.php" method="post" id="change_password_form" onsubmit="return false;">
				<p>Write the new password:</p>
				<p><input type="hidden" value="<?php if (isset($current_token)) { print $current_token; } ?>" name="token_change" /></p>
				<p><input type="password" title="Your Password" alt="Your Password" name="password" /></p>
				<p><input type="password" title="Type Your Password Again" alt="Type Your Password Again" name="password_verify" /></p><?php
					$x = mt_rand(10,20);
					$y = mt_rand(1,20);
					$_SESSION['photoblog_token_captcha'] = $x+$y;
					print "<br />\n<p>Solve the next sum: $x+$y</p>\r\n";
					print '<p><input type="text" alt="Captcha" title="Captcha" name="captcha" /></p>'."\r\n";
				?><p><button title="Change Password" id="change_password" type="button" name="change-password">Change Password</button></p>
				</form>
				<br />
				<div id="password-change"></div>
				<br />
				<p><a href="../index.php" title="Go back to photoblog">Go back to photoblog</a>&nbsp;|&nbsp;<a href="index.php" title="Go to login page">Go to login page</a></p>
				<br />
			</div>
	</body>
</html>