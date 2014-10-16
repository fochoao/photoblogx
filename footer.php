<?php
	require_once('pbadmin/config.php');
	$thumbnail_dir = "thumbnails/";
	$photo_dir = "images/";
    $query_thumbs_id = $db_connection->prepare("SELECT photo_id, photo_name, photo_file FROM photoblog_photo WHERE photo_id < ? ORDER BY photo_id DESC LIMIT 6;");
	$query_thumbs_id->execute(array($photo_id+1));
	$result_thumbs = $query_thumbs_id->fetchAll(PDO::FETCH_ASSOC);
    $query_thumb = $db_connection->prepare("SELECT photo_id, photo_name, photo_file FROM photoblog_photo WHERE photo_id = ?;");
	$query_thumb->execute(array($photo_id));
	$result_thumb = $query_thumb->fetch(PDO::FETCH_ASSOC);
	$current_thumbnail_id = $result_thumb['photo_id'];
	$current_thumbnail_name = $result_thumb['photo_name'];
	$current_thumbnail_file = $result_thumb['photo_file'];
	foreach ($result_thumbs as $thumbnail) {
		$thumbnail_file = $thumbnail['photo_file'];
		$thumbnail_name = $thumbnail['photo_name'];
		$thumbnail_id = $thumbnail['photo_id'];
		if (!empty($thumbnail_id)) {
			print '<a href="index.php?show_image='.$thumbnail_id.'" title="'.$thumbnail_name.'" alt="'.$thumbnail_name.'">';
			print '<img src="'.$thumbnail_dir.$thumbnail_file.'" title="'.$thumbnail_name.'" alt="'.$thumbnail_name.'" /></a>'."\n";
		}
	}
?>