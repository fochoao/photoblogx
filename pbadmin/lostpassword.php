<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
				$(document).on("click", '#send_email', function(){
					var field_email= $('input[name="email"]').prop('value');
					$.ajax({
						url: 'sendtoken.php',
						type: 'POST',
						data: { email: field_email },
						dataType: 'json',
						success: function(lost_pass) {
							if (lost_pass.password.token == "yes") {
								$("#token-sent").html("<p>The recovery info has been sent to Your email address.</p>");
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
				<form action="sendtoken.php" method="post" id="recover_email" onsubmit="return false;">
				<p>Write the email You login with</p>
				<p><input type="text" title="Your email" alt="Your email" name="email" /></p>
				<p><button title="Send e-mail" id="send_email" type="button" name="send-email">Send e-mail</button></p>
				</form>
				<br />
				<div id="token-sent"></div>
				<br />
				<p><a href="../index.php" title="Go back to photoblog">Go back to photoblog</a>&nbsp;|&nbsp;<a href="index.php" title="Go to login page">Go to login page</a></p>
				<br />
			</div>
	</body>
</html>
