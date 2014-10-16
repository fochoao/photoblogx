<?php
	require_once('login.php');
	require_once('sanitize.php');
    if (!empty($_GET["category-name"])) {
    	$user_id = $logged_user_id;
    	$title_category = sanitize($_GET["category-name"]);
		$description_category = sanitize($_GET["category-description"]);
		$query_category = $db_connection->prepare('INSERT INTO photoblog_category (category_name, category_description, category_user) VALUES (?, ?, ?);');
		$query_category->execute(array($title_category,$description_category,$user_id));
		$json = array("category"=>array("title"=>$title_category,"description"=>$description_category,"add"=>"yes"));
		print json_encode($json);
    }
	if (!empty($_GET['listcategories']) && $_GET['listcategories'] == "yes") {
		$list_query = $db_connection->prepare('SELECT * FROM photoblog_category;');
		$list_query->execute();
		$list = $list_query->fetchAll(PDO::FETCH_ASSOC);
		$category_print = '';
		foreach ($list as $category) {
			$id_category = $category['category_id'];
			$title_category = $category['category_name'];
			$description_category = $category['category_description'];
			$category_print .= '<br />'."\n".'<span id="link-category"><a href="categories.php?edit_category=yes&id_category='.$id_category.'&title_category='.$title_category.'&description_category='.$description_category;
			$category_print .= '" title="'.$title_category.'" alt="'.$title_category.'">'.$title_category.'</a></span>'."\n".'<br />'."\n";
		}
		if (!empty($id_category)) {
			print "<p>Click on the category title to edit or delete</p>\n";
			print $category_print;
		}
	}
	if (!empty($_GET["edit_category"]) && $_GET['edit_category'] == "yes") {
		$id_category = sanitize($_GET["id_category"]);
		$title_category = sanitize($_GET["title_category"]);
		$description_category = sanitize($_GET["description_category"]);
		$json = array("category"=>array("id"=>$id_category,"title"=>$title_category,"description"=>$description_category,"edit"=>"yes"));
		print json_encode($json);
	}
	if (!empty($_GET['id_edit']) && is_numeric($_GET['id_edit'])) {
		$id_category = sanitize($_GET['id_edit']);
		$title_category = sanitize($_GET['category_name']);
		$description_category = sanitize($_GET['category_description']);
		$update_category = $db_connection->prepare('UPDATE photoblog_category SET category_name = ?, category_description = ? WHERE category_id = ?;');
		$update_category->execute(array($title_category,$description_category,$id_category));
		$json = array("category"=>array("title"=>$title_category,"change"=>"yes"));
		print json_encode($json);
	}
	if (!empty($_GET['id_delete']) && is_numeric($_GET['id_delete'])) {
		$id_del = sanitize($_GET['id_delete']);
		$title_category = sanitize($_GET['category_name']);
		$query_delete = $db_connection->prepare('DELETE FROM photoblog_category WHERE category_id = ?;');
		$query_delete->execute(array($id_del));
		$query_delete_categories = $db_connection->prepare('DELETE FROM photoblog_categories WHERE categories_category_id = ?;');
		$query_delete_categories->execute(array($id_del));
		$json = array("category"=>array("title"=>$title_category,"confirm"=>"yes"));
		print json_encode($json);
	}
?>