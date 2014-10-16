<?php
	require_once('login.php');
	$categories_query = $db_connection->prepare('SELECT category_id, category_name FROM photoblog_category;');
	$categories_query->execute();
	$category_get = $categories_query->fetchAll(PDO::FETCH_ASSOC);
	print '<div id="photocategory">'."\n".'<span>Categories</span>'."\n".'<br /><br />'."\n";
	foreach ($category_get as $category) {
		$category_id = $category["category_id"];
		$category_name = $category["category_name"];
		if (!empty($category_name) || $category_name != "" || $category_name == null) {
			print '<a href="managephotos.php?load_photos=changed&load_category='.$category_id.'" alt="'.$category_name.'" title="'.$category_name.'">'.$category_name.'</a>&nbsp;&nbsp;'."\n";
		}
	}
	print '<a href="managephotos.php?load_photos=changed&load_category=none" title="Photos without Category" alt="Photos without Category">Photos without category</a>'."\n<br /><br />\n</div>\n";
	print '<div id="fillphotos"></div>'."\n";
?>