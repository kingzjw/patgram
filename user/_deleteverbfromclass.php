<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

if(!isset($_SESSION["userId"])) {
	exit("Error");
}

$classCode = $_POST["c"];
$verb = $_POST["v"];

$conn = createConnection();

if(($classId = getClassIdByCode($conn, $classCode))==null) {
	closeConnection($conn);
	echo json_encode(false);
	exit();
}

$result = mysqli_query($conn, "SELECT verbId
		FROM verb
		WHERE value='$verb'");

if($row = mysqli_fetch_array($result)) {
	$verbId = $row["verbId"];
	mysqli_query($conn, "DELETE FROM class_verb
		WHERE classId=$classId AND verbId=$verbId");
	echo json_encode(true);
}
else {
	echo json_encode(false);
}

closeConnection($conn);

?>