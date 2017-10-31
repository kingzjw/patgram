<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

if(!isset($_SESSION["userId"])) {
	exit("Error");
}

$classCode = $_POST["c"];
$meaningGroupId = $_POST["g"];

$conn = createConnection();

if(($classId = getClassIdByCode($conn, $classCode))==null) {
	closeConnection($conn);
	echo json_encode(false);
	exit();
}

$result = mysqli_query($conn, "INSERT INTO meaning_group_highlighted_in_class(classId, meaningGroupId)
		VALUES($classId, $meaningGroupId)");

if($result) {
	echo json_encode(true);
}
else {
	echo json_encode(false);
}

closeConnection($conn);

?>