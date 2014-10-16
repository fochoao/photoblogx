<?php
	require_once('pbadmin/config.php');
	require_once('pbadmin/sanitize.php');
	$archive_category_query = $db_connection->prepare('SELECT category_id, category_name FROM photoblog_category;');
	$archive_category_query->execute();
	$category_archive = $archive_category_query->fetchAll(PDO::FETCH_ASSOC);
	print "<br />\n";
	foreach ($category_archive as $category) {
		$category_id = $category["category_id"];
		$category_name = $category["category_name"];
		if (!empty($category_name) || $category_name != "" || $category_name == null) {
			print '<span id="archive-sort"><a href="archive.php?load_category='.$category_id.'" alt="'.$category_name.'" title="'.$category_name.'">'."$category_name</a></span><span>&nbsp;&nbsp;-&nbsp;</span>\n";
		}
	}
	print '<span id="archive-sort"><a href="archive.php?load_category=none" title="Photos without category" alt="Photos without category">Photos without category</a></span>'."\n<br />\n<br />\n";
	if (!empty($_GET['load_category']) && is_numeric($_GET['load_category'])) {
		$category_id = sanitize($_GET['load_category']);
		$archive_categories_query = $db_connection->prepare('SELECT categories_photo_id FROM photoblog_categories WHERE categories_category_id = ? ORDER BY categories_photo_id DESC;');
		$archive_categories_query->execute(array($category_id));
		$categories_result = $archive_categories_query->fetchAll(PDO::FETCH_ASSOC);
		print '<div id="archive-thumbnails">'."\n";
		foreach ($categories_result as $category) {
			$photo_id = $category['categories_photo_id'];
		   	$query_thumbnail = $db_connection->prepare('SELECT photo_id, photo_file, photo_name FROM photoblog_photo WHERE photo_id = ?;');
			$query_thumbnail->execute(array($photo_id));
			$thumbnail = $query_thumbnail->fetch(PDO::FETCH_ASSOC);
			$photo_id = $thumbnail['photo_id'];
			$photo_file = $thumbnail['photo_file'];
			$photo_name = $thumbnail['photo_name'];
			print '<span><a href="index.php?show_image='.$photo_id.'" title="'.$photo_name.'" alt="'.$photo_name.'"><img title="'.$photo_name.'" alt="'.$photo_name.'" src="thumbnails/'.$photo_file.'" /></a></span>'."\n";
		}
		print "</div>\n";
	} else if (!empty($_GET['load_category']) && $_GET['load_category'] == 'none') {
		$photo_dir = 'thumbnails/';
		$photos_query = $db_connection->prepare('SELECT photo_id, photo_file, photo_name FROM photoblog_photo ORDER BY photo_id DESC;');
		$photos_query->execute();
		$photos_result = $photos_query->fetchAll(PDO::FETCH_ASSOC);
		print '<div id="archive-thumbnails">'."\n";
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
				print '<span><a href="index.php?show_image='.$photo_id.'" title="'.$photo_name.'" alt="'.$photo_name.'"><img title="'.$photo_name.'" alt="'.$photo_name.'" src="thumbnails/'.$photo_file.'" /></a></span>'."\n";
			}
		}
		print "</div>\n";
	}
?>