<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

if(!isset($_SESSION["userId"])) {
	exit("Error");
}

$userId = $_SESSION["userId"];

$conn = createConnection();

$result = mysqli_query($conn, "SELECT classId, name, code, description, createDate
		FROM class
		WHERE userId=$userId
		ORDER BY createDate DESC");

$classes = [];
while($row = mysqli_fetch_array($result)) {
	$classes[] = ["classId" => $row["classId"], "name" => $row["name"], "code" => $row["code"],
				"description" => $row["description"], "createDate" => $row["createDate"]];
}

closeConnection($conn);

echo json_encode($classes);

?>