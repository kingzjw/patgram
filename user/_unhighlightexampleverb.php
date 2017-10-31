<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

if(!isset($_SESSION["userId"])) {
	exit("Error");
}

$classCode = $_POST["c"];
$exampleVerbId = $_POST["ev"];

$conn = createConnection();

if(($classId = getClassIdByCode($conn, $classCode))==null) {
	closeConnection($conn);
	echo json_encode(false);
	exit();
}

$result = mysqli_query($conn, "DELETE FROM example_verb_highlighted_in_class
		WHERE classId=$classId AND exampleVerbId=$exampleVerbId");

if($result) {
	echo json_encode(true);
}
else {
	echo json_encode(false);
}

closeConnection($conn);

?>