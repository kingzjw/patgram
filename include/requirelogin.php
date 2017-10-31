<?php

if(!isset($_SESSION["userId"])) {
	$data = array(
		"r" => $_SERVER["REQUEST_URI"]
	);
	$query = http_build_query($data);
	header("location: http://".$_SERVER['HTTP_HOST']."/login.php?".$query);
	exit();
}

?>