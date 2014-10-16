<?php
	require_once('login.php');
    function make_thumb($directory,$thumb_name,$thumb_directory) {
		$nw=100;
		$nh=100;
		$img = $directory.$thumb_name;
		$thname = $thumb_directory.$thumb_name;
		$dimensions = getimagesize($img);
		$w=$dimensions[0];
		$h=$dimensions[1];
		if (stripos($thumb_name,'.jpg') || stripos($thumb_name,'.jpeg')) {
			$img2 = imagecreatefromjpeg($img);
		}
        if (stripos($thumb_name,'.gif')) {
        	$img2 = imagecreatefromgif($img);
		}
        if (stripos($thumb_name,'.png')) {
        	$img2 = imagecreatefrompng($img);
		}
		$thumb=imagecreatetruecolor($nw,$nh);
		$wm = $w/$nw;
		$hm = $h/$nh;
		$h_height = $nh/2;
		$w_height = $nw/2;
		if ($w > $h) {
			$adjusted_width = $w / $hm;
			$half_width = $adjusted_width / 2;
			$int_width = $half_width - $w_height;
			imagecopyresampled($thumb,$img2,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h); 
			imagejpeg($thumb,$thname,100);
		} elseif (($w < $h) || ($w == $h)){
			$adjusted_height = $h / $wm;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $h_height;
			imagecopyresampled($thumb,$img2,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h); 
			imagejpeg($thumb,$thname,100);
		} else {
			imagecopyresampled($thumb,$img2,0,0,0,0,$nw,$nh,$w,$h);
			imagejpeg($thumb,$thname,100);
		}
		imagedestroy($img2);
    }
?>