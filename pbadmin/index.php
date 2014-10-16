<?php
	require_once('login.php');
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="PhotoblogX" /><?php
			require_once('printhead.php');
		?><link href="../css/main.css" type="text/css" rel="stylesheet" />
		
		<script src="../js/jquery-1.8.3.js" type="text/javascript"></script>
		<script src="../js/tooltip.js" type="text/javascript"></script>
		<script type="text/javascript">
			function start() {
				$('body').fadeIn(1000);
			};
			$(document).ready(function (){
				$('body').hide();
				$('a[title],input[title]').Tooltip();
				$('input').focusin(function() {
					$(this).fadeTo(500, 0.90).css('background-color','#444');
				});
				$('input').focusout(function() {
					$(this).fadeTo(500, 0.40).css('background-color','#666');
				});
				$('input').focus(function() {
					$(this).fadeTo(500, 0.90).css('background-color','#444');
				});
			});
		</script>
		
	</head>
	<body onload="start();">
			<div class="header" id="header-margin">
				<p><a href="index.php"<?php require_once('titleprint.php'); ?></p>
			</div>
			<div class="login" id="login-margin">
				<p>Login</p>
				<br /><?php
					if (!empty($_SESSION['photoblog_max_tries']) && $_SESSION['photoblog_max_tries'] > 4) {
						print "<p>Login has been temporarily blocked</p>\n";
					} else {
						$x = mt_rand(10,20);
						$y = mt_rand(1,15);
						$_SESSION['photoblog_login_captcha'] = $x+$y; 
						print '<form action="login.php" method="post">'."\n";
						print '<p><input type="text" alt="Mail" title="Mail" name="email" /></p>'."\n";
						print '<p><input type="password" alt="Password" title="Password" name="password" /></p>'."\n";
						print "<br />\n<p>Solve the next sum: $x+$y</p>";
						print '<p><input type="text" alt="Captcha" title="Captcha" name="captcha" /></p>'."\n";
                		print "<br />\n";
                		print '<p><input type="submit" alt="Sign In" title="Sign In" name="signin" value="Sign In" /></p>'."\n";
					}
				?></form>
				<br />
				<p><a href="../index.php" title="Go back to photoblog">Go back to photoblog</a>&nbsp;|&nbsp;<a href="lostpassword.php" title="Lost Password?">Lost Password?</a></p>
				<br />
			</div>
	</body>
</html>