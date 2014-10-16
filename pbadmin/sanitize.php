<?php
	function sanitize($var) {
		$sanitize = stripcslashes($var);
		$chars = "'`";
		$sanitize = trim($sanitize ,$chars);
		$chars =  '\+"/-';
		$sanitize = trim($sanitize ,$chars);
		$sanitize = strip_tags($sanitize);
		return $sanitize;
	}
?>