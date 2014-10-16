<?php
    header('Content-Type: text/xml; charset=utf-8');
	if ($_SERVER['SERVER_PORT'] == '80') {
    	$link = 'http://'.$_SERVER['SERVER_NAME'].trim($_SERVER['PHP_SELF'],'feed.php').'index.php';
    	$feed = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
    } else if  ($_SERVER['SERVER_PORT'] == '443') {
    	$link = 'https://'.$_SERVER['SERVER_NAME'].trim($_SERVER['PHP_SELF'],'feed.php').'index.php';
    	$feed = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	}
    $language = 'en';
	require_once('pbadmin/config.php');
    $query_first_photo = $db_connection->prepare('SELECT photo_user FROM photoblog_photo ORDER BY photo_id DESC LIMIT 1;');
	$query_first_photo->execute();
    $query_photo_user = $query_first_photo->fetch(PDO::FETCH_ASSOC);
	$photo_user = $query_photo_user['photo_user'];
	$query_user = $db_connection->prepare('SELECT user_photoblog_title, user_photoblog_description FROM photoblog_user WHERE user_id = ?;');
	$query_user->execute(array($photo_user));
	$user_photoblog = $query_user->fetch(PDO::FETCH_ASSOC);
	$site_name = $user_photoblog['user_photoblog_title'];
	$site_description = $user_photoblog['user_photoblog_description'];
    $details = '<?xml version="1.0" encoding="utf-8" ?>'."\n"
	      .'<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'."\n"
	      .'<atom:link href="'.$feed.'" rel="self" type="application/rss+xml" />'."\n"
	      ."<channel>\n"
	      ."<title>$site_name</title>\n<link>$link</link>\n<description>$site_description</description>\n<language>$language</language>\n";
    $items = '';
    $query_feed_query = $db_connection->prepare('SELECT photo_id, photo_file, photo_name, photo_date, photo_time FROM photoblog_photo ORDER BY photo_id DESC LIMIT 10;');
	$query_feed_query->execute();
    $query_feed = $query_feed_query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($query_feed as $feed_result) {
		$id_photo_feed = $feed_result['photo_id'];
		$title_photo_feed = $feed_result['photo_name'];
		$file_photo_feed = $feed_result['photo_file'];
		$date = explode('-',$feed_result['photo_date']);
		$time = explode(':',$feed_result['photo_time']);
		$pubdate = date("D, d M Y G:i:s", mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]));
		$url_photo_feed = $link."?show_image=$id_photo_feed";
		$items .= "<item>\n<title>$title_photo_feed</title>\n<link>$url_photo_feed</link>\n<guid isPermaLink=".'"true">'."$url_photo_feed</guid>\n<pubDate>$pubdate</pubDate>\n<description>\n<![CDATA[ <img src=".'"thumbnails/'.$file_photo_feed;
		$items .= '" />'." ]]>\n</description>\n</item>\n";
    }
    $items .= "</channel>\n</rss>\n";
    echo $details.$items;
?>