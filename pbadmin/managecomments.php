<?php
	require_once('login.php');
    if (!empty($_GET['load_comments']) && isset($_GET['load_comments']) == true) {
    	require_once('config.php');
		$list_comments_query = $db_connection->prepare('SELECT comment_id, comment_name, comment_email, comment_content, comment_photo_id FROM photoblog_comments;');
		$list_comments_query->execute();
		$list_comments = $list_comments_query->fetchAll(PDO::FETCH_ASSOC);
		print '<div id="admin_comments"><br />'."\n";
		foreach ($list_comments as $comment) {
			$comment_id = $comment['comment_id'];
			$comment_name = $comment['comment_name'];
			$comment_email = $comment['comment_email'];
			$comment_content = $comment['comment_content'];
			$photo_id = $comment['comment_photo_id'];
			$photo_query = $db_connection->prepare('SELECT photo_file, photo_name FROM photoblog_photo WHERE photo_id = ?;');
			$photo_query->execute(array($photo_id));
			$photo = $photo_query->fetch(PDO::FETCH_ASSOC);
			$photo_dir = '../thumbnails/';
			$photo_filename = $photo['photo_file'];
			$photo_name = $photo['photo_name'];
			print '<img src="'.$photo_dir.$photo_filename.'" title="'.$photo_name.'" alt="'.$photo_name.'" />'."\n";
			print '<p>Name of commenter:&nbsp;'.$comment_name."</p>\n";
			if (isset($comment_email) && $comment_email != null && $comment_email != "") {
				print '<p>Email:&nbsp;'.$comment_email."</p>\n";
			}
			print '<p>Comment made:&nbsp;'.$comment_content."</p>\n";
			print '<a href="managecomments.php?erase_comment='.$comment_id.'" title="'.$comment_name.'" alt="'.$comment_name.'">Erase Comment</a><br /><br />'."\n";
		}
		print "</div>\n";
    }
	if (!empty($_GET['erase_comment']) && is_numeric($_GET['erase_comment'])) {
		require_once('sanitize.php');
		$comment_id = sanitize($_GET['erase_comment']);
		$delete_comment = $db_connection->prepare('DELETE FROM photoblog_comments WHERE comment_id = ?;');
		$delete_comment->execute(array($comment_id));
		$json = array("comment"=>array("erased"=>"yes"));
		print json_encode($json);
	}
?>