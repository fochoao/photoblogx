<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="PhotoblogX" /><?php
			require_once('pbadmin/printhead.php');
		?><link href="css/main.css" type="text/css" rel="stylesheet" />
		
		<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
		<script src="js/tooltip.js" type="text/javascript"></script>
		<script type="text/javascript">
			function start() {
				$("#loading").hide();
				$("#archive-category").fadeIn(3000);
			};
			$(document).ready(function (){
				$('#archive-category').hide();
				$('a[title],input[title],img[title]').Tooltip();
				$("#archive-category img").hover(
					function () {
						$(this).fadeTo(400, 0.30);
					},
					function () {
						$(this).fadeTo(400, 0.95);
				});
			});
		</script>
		
	</head>
	<body onload="start();">
			<div class="header" id="header-margin">
				<p><a href="index.php"<?php require_once('pbadmin/titleprint.php'); ?></p>
			</div>
			<div id="links">
		<p><a href="index.php" title="Start Page" alt="Start Page">Start Page</a>&nbsp;|&nbsp;<a href="archive.php" title="Archive of Photos" alt="Archive of Photos">Archive of Photos</a>&nbsp;|&nbsp;<a href="about.php" title="About" alt="About">About</a></p>
			</div>
			<br />
			<br />
			<br />
			<div id="archive" class="archive-margin">
				<p id="loading">Loading thumbnails...</p>
				<div id="archive-category"><p>:&nbsp;Categories&nbsp;:</p><?php
				require_once('sortphotos.php');
				?></div>
			</div>
			<br />
			<div id="login_index">
			<p><a href="feed.php" title="Subscribe To Feed" alt="Subscribe To Feed">Subscribe To Feed</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="pbadmin/index.php" title="Log In to Admin Area" alt="Log In to Admin Area">Log In to Admin Area</a></p><br />
			</div>
			<br />
	</body>
</html>