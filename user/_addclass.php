<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/shorturl.php';

if(!isset($_SESSION["userId"])) {
	exit("Error");
}

$name = $_POST["name"];
$description = $_POST["description"];
$code = generateShortUrl();
$createDate = date("Y-m-d H:i:s", time());
$userId = $_SESSION["userId"];

$conn = createConnection();

$result = mysqli_query($conn, "INSERT INTO class(name, code, description, userId, createDate)
		VALUES('$name', '$code', '$description', $userId, '$createDate')");

// $result = mysqli_query($conn, "INSERT INTO class(name, code, description)
// 		VALUES('aaa', 'dgfs', 'good')");

if($result) {
	echo json_encode(true);
}
else {
	echo json_encode(false);
}

closeConnection($conn);

?>