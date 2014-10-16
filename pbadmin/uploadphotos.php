<?php
	require_once('login.php');
	if (!empty($_FILES['uploadphoto'])) {		
		$upload_dir = '../images/';
		$thumbnail_dir = '../thumbnails/';
		$user_id = $logged_user_id;
		print '<form action="administrator.php" id="images" method="POST" name="send-info-photo">'."\n";
		foreach($_FILES['uploadphoto']['tmp_name'] as $key => $tmp_name ){
			$file_name = $_FILES['uploadphoto']['name'][$key];
			$file_size = $_FILES['uploadphoto']['size'][$key];
			$file_tmp = $_FILES['uploadphoto']['tmp_name'][$key];
			if (!empty($file_name) && !empty($file_size)) {
				if (preg_match("/(gif|jpg|jpeg|png)$/",strtolower($file_name))) {
					$get_timezone = $db_connection->prepare('SELECT user_timezone FROM photoblog_user WHERE user_id = ?;');
					$get_timezone->execute(array($user_id));
					$set_timezone = $get_timezone->fetch(PDO::FETCH_ASSOC);
					date_default_timezone_set($set_timezone['user_timezone']);
					$date = getdate();
					$photo_name_file = 'photo-'.mt_rand(1000, 3000).$date['mday'].strchr(strtolower($file_name), '.');
					$photo_path = $upload_dir.$photo_name_file;
					$thumbnail_path = $thumbnail_dir.$photo_name_file;
					if ($file_size > 4512100) {
						echo "<p>Image is larger than 4MB</p>\n";
					}
					if (!is_dir($upload_dir)) {
						mkdir($upload_dir, 0755);
						chmod($upload_dir, 0755);
					}
					if (!is_dir($thumbnail_dir)) {
						mkdir($thumbnail_dir, 0755);
						chmod($thumbnail_dir, 0755);
					}
					if (move_uploaded_file($file_tmp, $photo_path)) {
						$permissions = 0755;
						chmod($photo_path,$permissions);
						$date_photo = $date['year'].'-'.$date['mon'].'-'.$date['mday'];
						$time_photo = $date['hours'].':'.$date['minutes'].':'.$date['seconds'];
						$insert_photo_data = $db_connection->prepare('INSERT INTO photoblog_photo (photo_file, photo_name, photo_date, photo_time, photo_user) VALUES (?, ?, ?, ?, ?);');
						$insert_photo_data->execute(array($photo_name_file,$file_name,$date_photo,$time_photo,$user_id));
						require_once('thumbnail.php');
						make_thumb($upload_dir,$photo_name_file,$thumbnail_dir);
						print '<p>Photo Uploaded</p><br />'."\n".'<img src="'.$thumbnail_path.'" title="'.$file_name.'" alt="'.$file_name.'" /><br />'."\n";
						$photo_query = $db_connection->prepare('SELECT photo_id FROM photoblog_photo WHERE photo_file = ?;');
						$photo_query->execute(array($photo_name_file));
						$photo_result = $photo_query->fetch(PDO::FETCH_ASSOC);
						$photo_id = $photo_result["photo_id"];
						print '<p>Title of Photo (Required)</p><input type="text" name="title-'.$photo_id.'" alt="Title of Photo" title="Title of Photo" /><br />'."\n";
						print '<p>Description of Photo</p><input type="text" name="description-'.$photo_id.'" alt="Description of Photo" title="Description of Photo" /><br />'."\n";
						print '<p>Photo Tags (separate by commas)</p><input type="text" name="tags-'.$photo_id.'" alt="Photo Tags" title="Photo Tags" />'."\n".'<br /><br />'."\n";
						$query_categories = $db_connection->prepare('SELECT category_id, category_name FROM photoblog_category;');
						$query_categories->execute();
						$query_category = $query_categories->fetchAll(PDO::FETCH_ASSOC);
						foreach ($query_category as $category) {
							$category_id = $category["category_id"];
							$category_name = $category["category_name"];
							if (!empty($category_id) || $category_id != null) {
								print '<input type="checkbox" name="category-'.$category_id.'" /><span>'.$category_name.'</span>'."\n";
							}
						}
						print "<br /><br />\n";
					}
				}
			}
		}
		print '<br />'."\n".'<input type="submit" name="send-info" alt="Send Photo Information" title="Send Photo Information" value="Send Photo Information" />'."\n";
		print '</form>'."\n";
	}
	if (!empty($_POST['send-info'])) {
		$photo_id_select = $db_connection->prepare('SELECT photo_id FROM photoblog_photo;');
		$photo_id_select->execute();
		$photo_id_query = $photo_id_select->fetchAll(PDO::FETCH_ASSOC);
		$edited_print = "";
		foreach ($photo_id_query as $photo_query) {
			$check_id = $photo_query["photo_id"];
			if (!empty($_POST["title-$check_id"])) {
				$title_photo = sanitize($_POST["title-$check_id"]);
				$description_photo = sanitize($_POST["description-$check_id"]);
				$tags_photo = sanitize($_POST["tags-$check_id"]);
				$data_photo = $db_connection->prepare('UPDATE photoblog_photo SET photo_name = ?, photo_description = ?, photo_tags = ? WHERE photo_id = ?;');
				$data_photo->execute(array($title_photo, $description_photo, $tags_photo, $check_id));
				$query_category = $db_connection->prepare('SELECT category_id FROM photoblog_category;');
				$query_category->execute();
				$result_category = $query_category->fetchAll(PDO::FETCH_ASSOC);
				foreach ($result_category as $category) {
					$category_id = $category['category_id'];
					if (!empty($_POST["category-$category_id"]) && !empty($category_id)) {
						$query_insert_category = $db_connection->prepare('INSERT INTO photoblog_categories (categories_photo_id, categories_category_id) VALUES (?, ?);');
						$query_insert_category->execute(array($check_id,$category_id));
					}
				}
				$edited_print .= "<p>Photo with title $title_photo succesfully updated.</p>\n";
			}
		}
		if (!empty($edited_print)) {
			print $edited_print;
		}
	}
?>