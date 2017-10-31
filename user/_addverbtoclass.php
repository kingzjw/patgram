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

if(!($row = mysqli_fetch_array($result))) {
	closeConnection($conn);
	echo json_encode(false);
	exit();
}

$verbId = $row["verbId"];
mysqli_query($conn, "INSERT INTO class_verb(classId, verbId)
		VALUES($classId, $verbId)");

closeConnection($conn);

echo json_encode(true);

?>