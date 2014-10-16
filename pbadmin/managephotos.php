<?php
	require_once('login.php');
	require_once('sanitize.php');
	if (isset($_GET['load_photos']) == "changed" && !empty($_GET["load_category"]) && is_numeric($_GET["load_category"])) {
		$category_id = sanitize($_GET["load_category"]);
		$categories_query = $db_connection->prepare('SELECT categories_photo_id FROM photoblog_categories WHERE categories_category_id = ?;');
		$categories_query->execute(array($category_id));
		$categories_result = $categories_query->fetchAll(PDO::FETCH_ASSOC);
		print '<div id="photo_show">'."\n";
		foreach ($categories_result as $category) {
			$photo_id = $category["categories_photo_id"];
		   	$fill_photos = $db_connection->prepare('SELECT photo_id, photo_file, photo_name FROM photoblog_photo WHERE photo_id = ? ORDER BY photo_id ASC;');
			$fill_photos->execute(array($photo_id));
			$photo = $fill_photos->fetch(PDO::FETCH_ASSOC);
			$photo_id = $photo['photo_id'];
			$photo_file = $photo['photo_file'];
			$photo_name = $photo['photo_name'];
			print '<span id="photo-'.$photo_id.'">'."\n";
			print '<span id="photoedit"><img title="'.$photo_name.'" alt="'.$photo_name.'" src="../thumbnails/'.$photo_file.'" />'."\n";
			print '<a href="managephotos.php?photo_id='.$photo_id.'" title="Edit '.$photo_name.'" alt="Edit '.$photo_name.'">Edit&nbsp;&nbsp;</a></span>'."\n";
			print '<span id="photoerase"><a href="managephotos.php?erase_photo_id='.$photo_id.'" title="Erase '.$photo_name.'" alt="Erase '.$photo_name.'">Erase&nbsp;&nbsp;</a></span>'."\n";
			print "</span>\n";
		}
		print '</div>'."\n";
	} else if (isset($_GET['load_photos']) == "changed" && !empty($_GET["load_category"]) && $_GET["load_category"] == "none") {
		$photos_query = $db_connection->prepare('SELECT photo_id, photo_file, photo_name FROM photoblog_photo ORDER BY photo_id ASC;');
		$photos_query->execute();
		$photos_result = $photos_query->fetchAll(PDO::FETCH_ASSOC);
		print '<div id="photo_show">'."\n";
		foreach ($photos_result as $photo) {
			$photo_id = $photo['photo_id'];
			$photo_file = $photo['photo_file'];
			$photo_name = $photo['photo_name'];
			$category_check_query = $db_connection->prepare('SELECT categories_photo_id FROM photoblog_categories WHERE categories_photo_id = ?;');
			$category_check_query->execute(array($photo_id));
			$category_result = $category_check_query->fetchAll(PDO::FETCH_ASSOC);
			foreach ($category_result as $photo_check) {
				$photo_check_id = $photo_check["categories_photo_id"];
				if ($photo_check_id == $photo_id) {
					$print_photo = "no";
				}
			}
			if (isset($print_photo) && $print_photo == "no") {
				unset($print_photo);
			} else {
				print '<span id="photo-'.$photo_id.'">'."\n";
				print '<span id="photoedit"><img title="'.$photo_name.'" alt="'.$photo_name.'" src="../thumbnails/'.$photo_file.'" />'."\n";
				print '<a href="managephotos.php?photo_id='.$photo_id.'" title="Edit '.$photo_name.'" alt="Edit '.$photo_name.'">Edit&nbsp;&nbsp;</a></span>'."\n";
				print '<span id="photoerase"><a href="managephotos.php?erase_photo_id='.$photo_id.'" title="Erase '.$photo_name.'" alt="Erase '.$photo_name.'">Erase&nbsp;&nbsp;</a></span>'."\n";
				print "</span>\n";
			}
		}
		print '</div>'."\n";
	}
	if (!empty($_GET['photo_id']) && is_numeric($_GET['photo_id'])) {
		$photo_id = sanitize($_GET['photo_id']);
		$photo_query = $db_connection->prepare("SELECT photo_file, photo_name, photo_description, photo_tags FROM photoblog_photo WHERE photo_id = ?;");
		$photo_query->execute(array($photo_id));
		$photo_result = $photo_query->fetch(PDO::FETCH_ASSOC);
		$file_photo = $photo_result['photo_file'];
		$title_photo = $photo_result['photo_name'];
		$description_photo = $photo_result['photo_description'];
		$tags_photo = $photo_result['photo_tags'];
		print '<div id="edit-photo">'."\n".'<form action="modifyphoto.php" enctype="multipart/form-data" id="photosmodify" method="post">'."\n";
		print '<img src="../thumbnails/'.$file_photo.'" title="'.$title_photo.'" alt="'.$title_photo.'" /><br />'."\n".'<input type="hidden" name="modify-photo" value="yes" />'."\n";
		print '<p>Title of photo (Required)</p><input type="text" name="title-'.$photo_id.'" alt="Title of Photo" title="Title of Photo" value="'.$title_photo.'" /><br />'."\n";
		print '<p>Description of photo</p><input type="text" name="description-'.$photo_id.'" alt="Description of Photo" title="Description of Photo" value="'.$description_photo.'" /><br />'."\n";
		print '<p>Photo tags (separate by commas)</p><input type="text" name="tags-'.$photo_id.'" alt="Photo Tags" title="Photo Tags" value="'.$tags_photo.'" id="phototags" /><br />'."\n";
		print '<p>Replace photo</p><input type="file" name="file-'.$photo_id.'" alt="Replace Photo" title="Replace Photos" id="photoreplace" /><br /><br />'."\n";
		$query_categories = $db_connection->prepare('SELECT category_id, category_name FROM photoblog_category;');
		$query_categories->execute();
		$query_category = $query_categories->fetchAll(PDO::FETCH_ASSOC);
		foreach ($query_category as $category) {
			$category_id = $category["category_id"];
			$category_name = $category["category_name"];
			$query_check_category = $db_connection->prepare('SELECT categories_category_id FROM photoblog_categories WHERE categories_photo_id = ? AND categories_category_id = ?;');
			$query_check_category->execute(array($photo_id,$category_id));
			$check_category = $query_check_category->fetch(PDO::FETCH_ASSOC);
			if (isset($check_category["categories_category_id"])) {
				print '<input type="checkbox" name="category-'.$category_id.'" checked="" /><span>'.$category_name.'</span>'."\n";
			} else if (!empty($category_id)) {
				print '<input type="checkbox" name="category-'.$category_id.'" /><span>'.$category_name.'</span>'."\n";
			}
		}
		print '<br />'."\n";
		print '</form>'."\n</div>\n";
	}
	if (!empty($_GET['erase_photo_id']) && is_numeric($_GET['erase_photo_id'])) {
		$photo_id = sanitize($_GET['erase_photo_id']);
		$query_delete = $db_connection->prepare('SELECT * FROM photoblog_photo WHERE photo_id = ?;');
		$query_delete->execute(array($photo_id));
		$photo_delete = $query_delete->fetch(PDO::FETCH_ASSOC);
		$check_photo_id = $photo_delete["photo_id"];
		$check_photo_file = $photo_delete["photo_file"];
		$delete_query = $db_connection->prepare('DELETE FROM photoblog_photo WHERE photo_id = ?;');
		$delete_query->execute(array($check_photo_id));
		$delete_categories = $db_connection->prepare('DELETE FROM photoblog_categories WHERE categories_photo_id = ?;');
		$delete_categories->execute(array($check_photo_id));
		$thumbnail = "../thumbnails/".$check_photo_file;
		$images = "../images/".$check_photo_file;
		unlink($thumbnail);
		unlink($images);
		$json = array("photo"=>array("id"=>$photo_id,"modified"=>"yes"));
		print json_encode($json);
	}
?>