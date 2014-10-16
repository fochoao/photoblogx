<?php
	require_once('pbadmin/config.php');
	require_once('pbadmin/sanitize.php');
	if (!empty($_GET['show_image']) && is_numeric($_GET['show_image']) || empty($_GET['show_image'])) {
		if (!empty($_GET['show_image']) && is_numeric($_GET['show_image'])) {
			$photo_id = sanitize($_GET["show_image"]);
	    	$query_photo = $db_connection->prepare('SELECT photo_id FROM photoblog_photo WHERE photo_id = ? ORDER BY photo_id DESC LIMIT 1;');
			$query_photo->execute(array($photo_id));
			$photo_result = $query_photo->fetch(PDO::FETCH_ASSOC);
			$photo_id = $photo_result['photo_id'];
		} else if (empty($_GET['show_image']) || !is_numeric($_GET['show_image'])) {
		    $query_photo = $db_connection->prepare('SELECT photo_id FROM photoblog_photo ORDER BY photo_id DESC LIMIT 1;');
			$query_photo->execute();
			$photo_result = $query_photo->fetch(PDO::FETCH_ASSOC);
			$photo_id = $photo_result['photo_id'];
		}
		$x = mt_rand(10,30);
		$y = mt_rand(1,20);
		$z = mt_rand(1,9);
		$captcha_sum = $x+$y+$z;
		$_SESSION['photoblog_captcha'] = $captcha_sum;
		$photo_dir = "images/";
		$query_photo = $db_connection->prepare('SELECT photo_file, photo_name, photo_description, photo_date, photo_time, photo_tags FROM photoblog_photo WHERE photo_id = ?;');
		$query_photo->execute(array($photo_id));
		$result_photo = $query_photo->fetch(PDO::FETCH_ASSOC);
		$photo_file = $result_photo['photo_file'];
		$photo_name = $result_photo['photo_name'];
		$photo_description = $result_photo['photo_description'];
		$photo_date = $result_photo['photo_date'];
		$photo_time = $result_photo['photo_time'];
		$photo_tags = $result_photo['photo_tags'];
		if (!empty($photo_id) && is_numeric($photo_id)) {
			print "\n".'<div class="content" id="content-margin">'."\n";
			print "\n".'<p id="image-loading">Loading image...</p>';
			$query_photo_prev = $db_connection->prepare('SELECT photo_id, photo_name, photo_file FROM photoblog_photo WHERE photo_id < ? ORDER BY photo_id DESC LIMIT 1;');
			$query_photo_prev->execute(array($photo_id));
			$previous_photo = $query_photo_prev->fetch(PDO::FETCH_ASSOC);
			$previous_photo_id = $previous_photo['photo_id'];
			$previous_photo_name = $previous_photo['photo_name'];
			$previous_photo_file = $previous_photo['photo_file'];
			$query_photo_next = $db_connection->prepare('SELECT photo_id, photo_name, photo_file FROM photoblog_photo WHERE photo_id > ? ORDER BY photo_id ASC LIMIT 1;');
			$query_photo_next->execute(array($photo_id));
			$next_photo = $query_photo_next->fetch(PDO::FETCH_ASSOC);
			$next_photo_id = $next_photo['photo_id'];
			$next_photo_name = $next_photo['photo_name'];
			$next_photo_file = $next_photo['photo_file'];
			if (!isset($next_photo_id) || !is_numeric($next_photo_id)) {
				$query_photo_next = $db_connection->prepare('SELECT photo_id, photo_name, photo_file FROM photoblog_photo ORDER BY photo_id ASC LIMIT 1;');
				$query_photo_next->execute();
				$next_photo = $query_photo_next->fetch(PDO::FETCH_ASSOC);
				$next_photo_id = $next_photo['photo_id'];
				$next_photo_name = $next_photo['photo_name'];
				$next_photo_file = $next_photo['photo_file'];
			}
			if (!isset($previous_photo_id) || !is_numeric($previous_photo_id)) {
				$query_photo_prev = $db_connection->prepare('SELECT photo_id, photo_name, photo_file FROM photoblog_photo ORDER BY photo_id DESC LIMIT 1;');
				$query_photo_prev->execute();
				$previous_photo = $query_photo_prev->fetch(PDO::FETCH_ASSOC);
				$previous_photo_id = $previous_photo['photo_id'];
				$previous_photo_name = $previous_photo['photo_name'];
				$previous_photo_file = $previous_photo['photo_file'];
			}
			$image_size = getimagesize($photo_dir.$photo_file);
			$image_width = $image_size[0];
			$image_height = $image_size[1];
			if (isset($previous_photo_id)) {
				print '<p class="back"><a href="index.php?show_image='.$next_photo_id.'" id="previous-button" page="index.php?show_image='.$next_photo_id.'" photo="'.$photo_dir.$next_photo_file.'" title="Previous Image ['.$next_photo_name.']" alt="'.$next_photo_name.'">&lt;</a></p>'."\n";			}
			print '<div id="content-photo"><img src="'.$photo_dir.$photo_file.'" title="'.$photo_name.'" alt="'.$photo_name.'" page="index.php?show_image='.$photo_id.'" id="main_photo" /></div>'."\n";
			if (isset($next_photo_id)) {
				print '<p class="forward"><a href="index.php?show_image='.$previous_photo_id.'" id="forward-button" page="index.php?show_image='.$previous_photo_id.'" photo="'.$photo_dir.$previous_photo_file.'" title="Next Image ['.$previous_photo_name.']" alt="'.$previous_photo_name.'">&gt;</a></p>'."\n";
			}
			$image_ratio = $image_width/$image_height;
			$max_width = 740;
			$max_height = 460;
			$max_width_photobox = 780;
			$max_height_photobox = 520;
			print '</div>';
			print '<script type="text/javascript">'."\n";
			if (($image_width > $image_height) && ($image_ratio > 1.5)) {
				$height = ceil($image_height/$image_width*$max_width);
				$height_photobox = ceil($image_height/$image_width*$max_width_photobox);
				$margin_top = ceil(($max_height-$height)/2);
				$margin_top_photobox = ceil(($max_height_photobox-$height)/2);
				print "$('#main_photo').css({'height': $height , 'margin-top': $margin_top});\n";
			} else {
				$width = ceil($image_width/$image_height*$max_height);
				$width_photobox = ceil($image_width/$image_height*$max_height_photobox);
				$margin_left = ceil(($image_height*2)/2-70)."px";
				print "$('#main_photo').css({'width': $width});\n";
			}
			print '$(document).keypress(function(after) { if (after.keyCode == 39 || after.which == 100) { window.location.replace("index.php?show_image='.$previous_photo_id.'");}});'."\n";
			print '$(document).keypress(function(after) { if (after.keyCode == 37 || after.which == 97) { window.location.replace("index.php?show_image='.$next_photo_id.'");}});'."\n";
			print '</script>'."\n";
			print "</div>\n";
			print '<div id="info-photo" style="margin-top:25px;">'."\n".'<br />'."\n";
			print "<p>$photo_name</p>\n";
			if (!empty($photo_description) || $photo_description != "") {
				print "<p>$photo_description</p>\n";
			}
			if (!empty($photo_tags) || $photo_tags != "") {
				print "<p>$photo_tags</p>\n";
			}
			$categories_show_query = $db_connection->prepare('SELECT categories_category_id FROM photoblog_categories WHERE categories_photo_id = ?;');
			$categories_show_query->execute(array($photo_id));
			$categories_show = $categories_show_query->fetchAll(PDO::FETCH_ASSOC);
			$print_category = '<p>';
			foreach ($categories_show as $category) {
				$category_id = $category['categories_category_id'];
				$category_list_query = $db_connection->prepare('SELECT category_name FROM photoblog_category WHERE category_id = ?;');
				$category_list_query->execute(array($category_id));
				$category_fetch = $category_list_query->fetch(PDO::FETCH_ASSOC);
				$category_name = $category_fetch['category_name'];
				$print_category .= "$category_name&nbsp;&nbsp;";
				if (is_string($category_name)) {
					$do_print = true;
				}
			}
			$print_category .= "</p>\n";
			if (!empty($do_print) && $do_print == true) {
				print $print_category;
			}
			print "<p>Use Left and Right keys to navigate or A and D.</p>";
			$count_comments_query = $db_connection->prepare('SELECT COUNT(comment_content) FROM photoblog_comments WHERE comment_photo_id = ?;');
			$count_comments_query->execute(array($photo_id));
			$count_comments = $count_comments_query->fetchColumn();
			print '<br />'."\n";
			print '<div id="display-info"><span><a href="javascript:void(0);" title="Show Exif" alt="Show Exif" id="show-exif">Show Exif&nbsp;&nbsp;</a></span><span><a href="javascript: void(0);" id="show_form" title="Comment on Photo" alt="Comment on Photo">Comment on photo</a></span>&nbsp;&nbsp;<span class="comments-number"><a href="javascript: void(0)" id="show_comments" title="Watch comments" alt="Watch Comments">&nbsp;Watch comments (';
			if (!empty($count_comments) && is_numeric($count_comments)) {
				print $count_comments;
			} else if (empty($count_comments) || $count_comments == "") {
				print "0";
			}
			print ")</a></span></p>\n<br />\n<br />\n</div>\n";
			print '<form action="comment.php" method="get" id="make_comment" onsubmit="return false;">'."\n";
			print '<p><a href="javascript: void(0);" id="hide_form" title="Hide comment form" alt="Hide comment form">Hide comment form</a></p><br />'."\n";
			print '<input type="hidden" name="id_comment" value="'.$photo_id.'" id="comment" />'."\n";
			print '<p>Your Name&nbsp; (Required)&nbsp; <input type="text" name="name" id="name-comment" title="Your name" alt="Your Name" /></p>'."\n";
			print '<p>Mail&nbsp; (not required, but will be not shown)&nbsp; <input type="text" name="mail" id="mail-comment" title="Email" alt="Email" /></p>'."\n";
			print '<p>Comment&nbsp; (Required)&nbsp; <input type="text" name="comment" id="content-comment" title="Comment" alt="Comment" /></p>'."\n";
			print '<p>Solve the next sum&nbsp;&nbsp;</p><p>'.$x.'+'.$y.'+'.$z.'<input type="text" name="captcha" id="captcha-comment" title="Captcha" alt="Captcha" /></p>'."\n";
			print '<p><button id="insert-comment" name="insert-comment" type="button" title="Submit Comment">Submit Comment</button></p><br />';
			print '<div id="comment"></div>'."\n<br />\n</form>\n";
			print '<div id="comments_display">';
			$comments_query = $db_connection->prepare('SELECT comment_name, comment_content FROM photoblog_comments WHERE comment_photo_id = ?;');
			$comments_query->execute(array($photo_id));
			$comments_result = $comments_query->fetchAll(PDO::FETCH_ASSOC);
			foreach ($comments_result as $comment) {
				$comment_name = $comment['comment_name'];
				$comment_content = $comment['comment_content'];
				if (!empty($comment_name) && $comment_name != "" && !empty($comment_content) && $comment_content != "") {
					print "<p>Name: $comment_name</p>\n";
					print "<p>Comment: $comment_content</p><br />\n";
					$print_hide_comment = true;
				}
			}
			print '<span class="hide-comment">'."\n";
			if (isset($print_hide_comment)) {
				print '<p><a href="javascript: void(0);" id="hide_comments" title="Hide comments" alt="Hide comments">Hide comments</a></p><br /></span>'."\n";
			}
			print "</span></div>\n";
			print '<div id="exif">'."\n";
			$photo_format = explode('.', $photo_file);
			$photo_format = $photo_format[1];
			if ($photo_format == "jpg") {
				$exif_read = exif_read_data($photo_dir.$photo_file);
				$exif_array = is_array($exif_read);
			}
			if (!empty($exif_array)) {
				if (!empty($exif_read['Model'])) {
					print "<p>EXIF</p>";
					$exif_model = $exif_read['Model'];
					print "<p>Model $exif_model</p>\n";
				}
				if (!empty($exif_read["ApertureValue"])) {
					$exif_aperture = $exif_read["ApertureValue"];
					$exif_aperture = explode('/', $exif_aperture);
					$exif_aperture = $exif_aperture[0]/$exif_aperture[1];
					print "<p>Aperture $exif_aperture</p>\n";
				}
				if (!empty($exif_read['ExposureTime'])) {
					$exif_speed = $exif_read['ExposureTime'];
					print "<p>Speed $exif_speed</p>\n";
				}
				if (!empty($exif_read['ISOSpeedRatings'])) {
					$exif_iso = $exif_read['ISOSpeedRatings'];
					print "<p>ISO $exif_iso</p>\n";
				}
			}
			print "<p>Image Sent on $photo_date at $photo_time</p>\n";
			print '<p><a href="javascript: void(0);" title="Hide EXIF" alt="Hide Exif" id="hide-exif">Hide Exif</a></p><br /></div>'."\n";
			print "</div>\n";
		}
	}
?>