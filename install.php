<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="PhotoblogX" />
		
		<style type="text/css">
			body {
				margin: 0px 0px 0px 0px;
				background-color: #FFFFFF;
				font-size: 12px;
				font-family: Arial, Helvetica, sans-serif;
				color: #222222;
				text-align: center;
			}
			
			a {
				color: #FFFFFF;
				text-decoration: none;
				text-shadow: 0px 0px 4px #000000;
				font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 10px;
				font-weight: 200;
			}
			
			a:hover {
				color: #CCCCCC;
				text-decoration: none;
				text-shadow: 0px 0px 4px #000000;
				font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 10px;
				font-weight: 200;
			}
			
			#tooltip {
				padding: 4px;
				border: 1px solid #A6A7AB;
				color: #FFFFFF;
				background: #000000;
				opacity: .6; 
				filter: Alpha(Opacity=60);
				text-shadow: 0px 0px 6px #FFFFFF;
			}

			.header {
				margin: 0px auto;
				width: 800px;
				height: 60px;
				background: #222222;
				box-shadow: 0px 0px 10px #111111;
				-moz-box-shadow: 0px 0px 10px #111111;
				-webkit-box-shadow: 0px 0px 10px #111111;
				border-radius: 0px 0px 10px 10px;
				-webkit-border-radius: 0px 0px 10px 10px;
				-moz-border-radius: 0px 0px 10px 10px;
			}

			#header-margin {
				margin-top: 0px;
				padding-top: 15px;
			}

			.header p, .header a {
				text-shadow: 0px 0px 12px #BBBBBB;
				color: #CCCCCC;
				text-align: center;
				font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 16px;
				font-weight: 200;
			}

			.header a:hover {
				text-shadow: 0px 0px 12px #BBBBBB;
				color: #CD0A0A;
				text-align: center;
				font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 16px;
				font-weight: 200;
			}

			#install {
				margin: 0px auto;
				width: 790px;
				height: auto;
				background: #222222;
				box-shadow: 0px 0px 10px #111111;
				-moz-box-shadow: 0px 0px 10px #111111;
				-webkit-box-shadow: 0px 0px 10px #111111;
				border-radius: 10px 10px 10px 10px;
				-webkit-border-radius: 10px 10px 10px 10px;
				-moz-border-radius: 10px 10px 10px 10px;
			}

			.install-margin {
				margin-top: 40px;
				padding: 5px;
			}

			#install p, #install span {
				color: #FFFFFF;
				text-align: center;
				text-shadow: 0px 0px 3px #000000;
				font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 10px;
				font-weight: 200;
			}
			
			#install a {
				color: #FFFFFF;
				text-decoration: none;
				text-shadow: 0px 0px 4px #000000;
				font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 10px;
				font-weight: 200;
			}

			#install a:hover {
				color: #CCCCCC;
				text-decoration: none;
				text-shadow: 0px 0px 4px #000000;
				font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 10px;
				font-weight: 200;
			}
			
			#install input, #install button, #install select {
				color: #CCCCCC;
				background-color: #666666;
				border: none;
				text-decoration: none;
				text-shadow: 0px 0px 4px #FFFFFF;
				font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 10px;
				font-weight: 200;
			}
		</style>
		
		<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
		<script src="js/tooltip.js" type="text/javascript"></script>
		<script type="text/javascript">
			function start() {
				$('body').fadeIn(3000);
			};
			$(document).ready(function (){
				$('a[title],input[title],img[title],button[title]').Tooltip();
				$('body').hide();
				$(document).on("click", 'button[name="test-mysql"]', function() {
					var db_host = $('input[name="db_host"]').prop('value');
					var db_name = $('input[name="db_name"]').prop('value');
					var db_username = $('input[name="db_username"]').prop('value');
					var db_password = $('input[name="db_password"]').prop('value');
					$.ajax({
						type: $('#form-install').attr('method'),
						url: $('#form-install').attr('action'),
						dataType: 'json',
						data: { host_db: db_host, name_db: db_name, username_db: db_username, password_db: db_password },
						cache: false,
						success:  function (install_testdb) {
							if (install_testdb.db.succeed == "yes") {
								$("#testdb-result").html('<p style="color: green;">Database connection made. Proceed to fill the rest of fields.</p><br />');
							} else if (install_testdb.db.succeed == "no") {
								var db_error = install_testdb.db.err;
								$("#testdb-result").html('<p style="color: red;">Database connection failed.</p><p style="color: red;">Connection error: '+db_error+'</p><br />');
							} else {
								var db_notfilled = install_testdb.db.succeed;
								$("#testdb-result").html('<p style="color: red;">'+db_notfilled+'</p><br />');
							}
						}
					});
				});
				$(document).on("click", 'button[name="send-info"]', function() {
					var db_host = $('input[name="db_host"]').prop('value');
					var db_name = $('input[name="db_name"]').prop('value');
					var db_username = $('input[name="db_username"]').prop('value');
					var db_password = $('input[name="db_password"]').prop('value');
					var db_name_create = $('input[name="db_name_tocreate"]').prop('value');
					var mail = $('input[name="mail"]').prop('value');
					var password = $('input[name="password"]').prop('value');
					var password_check = $('input[name="password_check"]').prop('value');
					var timezone = $("#timezone option:selected").prop('value');
					var photo_title = $('input[name="title"]').prop('value');
					var photo_description = $('input[name="description"]').prop('value');
					var photo_keywords = $('input[name="keywords"]').prop('value');
					$.ajax({
						type: $('#form-install').prop('method'),
						url: 'installcreatedb.php',
						dataType: 'json',
						data: { host_db: db_host, name_db: db_name, username_db: db_username, password_db: db_password, db_namecreate: db_name_create, photo_mail: mail, photo_password: password, photo_password_check: password_check, photo_timezone: timezone, photoblog_title: photo_title, photoblog_description: photo_description, photoblog_keywords: photo_keywords },
						cache: false,
						success:  function (create_user) {
							if (create_user.photoblog.succeed == "yes" && create_user.photoblog.config == "created") {
								var user_for_login = create_user.photoblog.user;
								$("#photoblog-creation").html('<p style="color: green;">Database created, as well username, proceed to login into: </p><a href="pbadmin/index.php" title="Administration Area" alt="Administration Area">Administration Area</a><p>Your username to login is: '+user_for_login+'</p><br />');
							} else if (create_user.photoblog.succeed == "no") {
								$("#photoblog-creation").html('<p style="color: red;">Database creation failed or fields not filled. Check Your Settings.</p><br />');
							} else {
								var fields_notfilled = create_user.photoblog.succeed;
								$("#photoblog-creation").html('<p style="color: red;">'+fields_notfilled+'</p><br />');
							}
						}
					});
				});
			});
		</script>
		
	</head>
	<body onload="start();">
			<div class="header" id="header-margin">
				<p><a href="install.php" title="Install PhotoblogX" alt="Install PhotoblogX">Install PhotoblogX</a></p>
			</div>
			<br />
			<div id="install" class="install-margin">
				<p style="padding:20px;">All fields are necessary for the script to work properly, except for MySQL database name. Unless Your hosting allows only one database, fill the field MySQL database name with that database and leave the field MySQL database to create in blank.</p>
				<br />
				<form action="installtestdb.php" method="post" name="install" id="form-install" onsubmit="return false;">
					<p>MySQL host (usually localhost or an ip address) <input type="text" title="Database Host" alt="Database Host" name="db_host" /></p>
					<p>MySQL database name (not a necessary field, unless You created a database, type it here) <input type="text" title="Database Name" alt="Database Name" name="db_name" /></p>
					<p>MySQL database username <input type="text" title="Database Username" alt="Database Username" name="db_username" /></p>
					<p>MySQL database password <input type="password" title="Database Password" alt="Database Password" name="db_password" /></p>
					<p><span><button title="Test MySQL Connection" id="test_mysql" type="button" name="test-mysql">Test MySQL Connection</button></span></p>
					<br />
					<div id="testdb-result"></div>
					<p>MySQL database to create (leave blank if You have had already created a database) <input type="text" title="Database Name Creation" alt="Database Name Creation" name="db_name_tocreate" /></p>
					<p>Type your email <input type="text" title="User Email" alt="User Email" name="mail" /></p>
					<p>Type your password <input type="password" title="User Password" alt="User Password" name="password" /></p>
					<p>Type your password again <input type="password" title="User Password" alt="User Password" name="password_check" /></p>
					<p>Choose Your Timezone&nbsp;&nbsp;<?php include('pbadmin/showtimezone.php'); ?></p>
					<p>Photoblog title (this will be shown on the browser, as well on the photoblog header) <input type="text" title="Photoblog Title" alt="Photoblog Title" name="title" /></p>
					<p>Photoblog description (used by search engines, add an accurate description) <input type="text" title="Photoblog Description" alt="Photoblog Description" name="description" /></p>
					<p>Photoblog keywords (used by search engines as well) <input type="text" title="Photoblog Keywords" alt="Photoblog Keywords" name="keywords" /></p>
					<p><span><button title="Submit" id="send_info" type="button" name="send-info">Submit</button></span></p>
					<br />
					<div id="photoblog-creation"></div>
				</form>
				<p>The username password can later be changed, as well photoblog settings.</p>
				<br />
			</div>
			<br />
			<br />
	</body>
</html>