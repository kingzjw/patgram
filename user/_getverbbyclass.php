<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

if(!isset($_SESSION["userId"])) {
	exit("Error");
}

$classCode = $_GET["c"];

$conn = createConnection();

$result = mysqli_query($conn, "SELECT A.value
		FROM verb AS A
		INNER JOIN class_verb AS B ON A.verbId=B.verbId
		INNER JOIN class AS C ON B.classId=C.classId
		WHERE C.code='$classCode'
		ORDER BY A.value");

$verbs = [];
while($row = mysqli_fetch_array($result)) {
	$verbs[] = $row["value"];
}

closeConnection($conn);

echo json_encode($verbs);

?>