<?php
    if (!empty($_POST['host_db']) && !empty($_POST['username_db']) && !empty($_POST['password_db'])) {
    	require_once('pbadmin/sanitize.php');
		$db_hostname = sanitize($_POST['host_db']);
		if (!empty($_POST['name_db'])) {
			$db_name = sanitize($_POST['name_db']);
			$db_dsn_host = "mysql:host=$db_hostname;dbname=$db_name";
		} else {
			$db_dsn_host = "mysql:host=$db_hostname;";
		}
		$db_username = sanitize($_POST['username_db']);
		$db_password = sanitize($_POST['password_db']);
		$db_options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		);
		try {
			$db_connection = new PDO($db_dsn_host, $db_username, $db_password, $db_options);
		} catch (PDOException $db_error) {
			$error_db = true;
		}
		if (!isset($error_db)) {
			$json = array("db"=>array("succeed"=>"yes"));
		} else if ($error_db) {
			$json = array("db"=>array("succeed"=>$db_error->getMessage()));
		}
		print json_encode($json);
    } else {
    	$json = array("db"=>array("succeed"=>"MySQL fields not filled."));
		print json_encode($json);
    }
?>