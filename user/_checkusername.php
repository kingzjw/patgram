<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

$userName = $_POST["name"];

$conn = createConnection();

$result = mysqli_query($conn, "SELECT userId FROM user
		WHERE userName='$userName'");

if(mysqli_num_rows($result)==0) {
	echo json_encode(true);
}
else {
	echo json_encode(false);
}

closeConnection($conn);

?>