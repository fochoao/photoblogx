<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="PhotoblogX" /><?php
			require_once('pbadmin/printhead.php');
		?><link href="css/main.css" type="text/css" rel="stylesheet" />
		<!--[if IE 6]>
			<link href="css/ie.css" type="text/css" rel="stylesheet" />
		<![endif]-->
		<!--[if IE 7]>
			<link href="css/ie.css" type="text/css" rel="stylesheet" />
		<![endif]-->
		<link rel="alternate" href="feed.php" title="RSS feed" type="application/rss+xml" />
		
		<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
		<script src="js/jquery-ui-1.10.3.js" type="text/javascript"></script>
		<script src="js/tooltip.js" type="text/javascript"></script>
		<script type="text/javascript">
			function start() {
				$('#image-loading').hide();
				$('#thumbs-loading').hide();
				$('#main_photo').fadeIn(3600);
				$('#thumbs').show();
			};
			function playslideshow(play) {
				if (play == "play") {
					var get_id = $('#forward-button').attr("page");
					setTimeout(function() {
						document.location.href = get_id+"&slideshow=playing";
					},9000);
					$("#start_slideshow").prop('src','css/stop.png');
					$("#start_slideshow").fadeTo("slow", .5);
				} else if (play == "stop") {
					$("#start_slideshow").fadeTo("slow", 1);
					var get_id = $('#main_photo').attr("page");
					document.location.href =  get_id;
				}
			};
			function loadphoto() {
				$('#thumbs').hide();
				$('#exif').hide();
				$('a[title],input[title],img[title],button[title]').Tooltip();
				$('.back, .forward, input').fadeTo(500, 0.40).css('background-color','#333333')
				$('.back, .forward').hover(
					function () {
						$(this).fadeTo(500, 0.90).css('background-color','#111111');
					},
					function () {
						$(this).fadeTo(500, 0.40).css('background-color','#333333');
				});
				$('input').focusin(function() {
					$(this).fadeTo(500, 0.90).css('background-color','#666');
				});
				$('input').focusout(function() {
					$(this).fadeTo(500, 0.40).css('background-color','#444');
				});
				$("#show_form").click(function(){
					$('#make_comment').fadeIn(900);
				});
				$("#hide_form").click(function(){
					$('#make_comment').fadeOut(900);
				});
				$("#show_comments").click(function(){
					$('#comments_display').fadeIn(900);
				});
				$("#hide_comments").click(function(){
					$('#comments_display').fadeOut(900);
				});
				$("#show-exif").click(function(){
					$('#exif').fadeIn(900);
				});
				$("#hide-exif").click(function(){
					$('#exif').fadeOut(900);
				});
				$(document).on("click", 'button[name="insert-comment"]', function(){
					var id_comment = $('input#comment').prop("value");
					var name_comment = $('input#name-comment').prop("value");
					var mail_comment = $('input#mail-comment').prop("value");
					var content_comment = $('input#content-comment').prop("value");
					var captcha = $('input#captcha-comment').prop("value");
					$.ajax({
						type: 'GET',
						url: 'comment.php',
						data: { comment_id : id_comment, comment_name : name_comment, comment_mail : mail_comment, comment_content : content_comment, comment_captcha : captcha },
						dataType: 'json',
						success: function(response) {
							if (response != null && response.comment.insert == "yes") {
								var comment_name = response.comment.name;
								var comment_content = response.comment.content;
								var comment_number = response.comment.number;
								$("div#comment").php('<p>Comment succesfully sent.</p><p>Name: '+comment_name+'</p><p>Comment: '+comment_content+'</p><br />');
								$(".comments-number").php('<a href="javascript: void(0)" id="show_comments" title="Watch comments" alt="Watch Comments">&nbsp;Watch comments ('+comment_number+')</a>');
								$("#comments_display").prepend('<p>Name:&nbsp;'+comment_name+'</p><p>Comment:&nbsp;'+comment_content+'<p><br />');
								$(".hide-comment").php('<p><a href="javascript: void(0);" id="hide_comments" title="Hide comments" alt="Hide comments">Hide comments</a></p><br />');
								$('a[title],input[title],img[title],button[title]').Tooltip();
								$("#show_comments").click(function(){
									$('#comments_display').fadeIn(900);
								});
								$("#hide_comments").click(function(){
									$('#comments_display').fadeOut(900);
								});
							} else if (response == null) {
								$("div#comment").php("<p>Either You didn't fill needed fields or captcha was wrong.</p><p>Refresh Page or type F5 to add comment properly.</p>");
							}
						},
						error: function(response) {
							$("div#comment").php("<p>Either You didn't fill needed fields or captcha was wrong.</p><p>Refresh Page or type F5 to add comment properly.</p>");
						}
					});
				});
				$(".footer img").hover(
					function () {
						$(this).fadeTo(400, 0.30);
					},
					function () {
						$(this).fadeTo(400, 0.95);
				});
			};
			jQuery(document).ready(function() {
				loadphoto();
				start();<?php
				if ($_SERVER['SERVER_PORT'] == '80') {
				  $site = 'http://'.$_SERVER['SERVER_NAME'].trim($_SERVER['PHP_SELF'], 'index.php');
				} else if  ($_SERVER['SERVER_PORT'] == '443') {
				  $site = 'https://'.$_SERVER['SERVER_NAME'].trim($_SERVER['PHP_SELF'], 'index.php');
				}
				?>$(document).on('click', '#forward-button', function(){
					var page1 = $('#forward-button').attr("page");<?php
					print "\nwindow.location.replace('$site'+page1);\n";
					?>$("body").load(page);
				});
				$(document).on('click', '#previous-button', function(){
					var page2 = $('#previous-button').attr("page");<?php
					print "\nwindow.location.replace('$site'+page2);\n";
					?>$("body").load(page2);
				});<?php
					if (!empty($_GET["slideshow"]) == "playing") {
						print "\n".'playslideshow("play");'."\n";
					}
				?>
				$(document).on('click', '#start_slideshow', function(){
					playslideshow("play");<?php
					if (!empty($_GET["slideshow"]) == "playing") {
						print "\n".'playslideshow("stop");'."\n";
					}
					?>$("#start_slideshow").fadeTo("slow", .5);
				});
			});
		</script>
		
	</head>
	<body onload="start();">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="header" id="header-margin">
				<p><a href="index.php"<?php require_once('pbadmin/titleprint.php'); ?></p>
			</div>
			<div id="links">
				<p><a href="index.php" title="Start Page" alt="Start Page">Start Page</a>&nbsp;|&nbsp;<a href="archive.php" title="Archive of Photos" alt="Archive of Photos">Archive of Photos</a>&nbsp;|&nbsp;<a href="about.php" title="About" alt="About">About</a></p>
			</div>
			<br />
			<p style="margin:0px auto;"><span><img src="css/play.png" title="Start Slideshow" alt="Start Slideshow" id="start_slideshow" /></span></p>
			<br /><?php
				include('photo.php');
			?><div class="footer" id="footer-margin">
			<p id="thumbs-loading">Loading thumbnails...</p><div id="thumbs"><?php
				include('footer.php');
			?></div></div>
			<br />
			<br />
			<div id="socialbuttons" class="socialbuttons"><?php
				include('socialbuttons.php');
			?><script type="text/javascript">
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
				!function(d,s,id){
					var js,fjs=d.getElementsByTagName(s)[0];
					if(!d.getElementById(id)){js=d.createElement(s);
						js.id=id;
						js.src="//platform.twitter.com/widgets.js";
						fjs.parentNode.insertBefore(js,fjs);}}
				(document,"script","twitter-wjs");
			</script>
			</div>
			<br />
			<br />
			<div id="login_index">
			<p><a href="feed.php" title="Subscribe To Feed" alt="Subscribe To Feed">Subscribe To Feed</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="pbadmin/index.php" title="Log In to Admin Area" alt="Log In to Admin Area">Log In to Admin Area</a></p><br />
			</div>
			<br />
	</body>
</html>