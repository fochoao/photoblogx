<?php
	if ($_SERVER['SERVER_PORT'] == '80') {
    	$link = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?show_image='.$photo_id;
    } else if  ($_SERVER['SERVER_PORT'] == '443') {
    	$link = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?show_image='.$photo_id;
	}
	print '<div style="0px auto;">'."\n";
	print '<div class="fb-like" data-href="'.$link.'" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="true" data-send="false"></div>';
    print "\n".'<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$link.'">Tweet</a>';
    print "\n".'<div class="g-plusone" data-annotation="inline" data-width="140" data-size="small" data-href="'.$link.'"></div></div>';
?>