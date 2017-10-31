<?php

function createConnection() {
	$conn = mysqli_connect("localhost", "admin", "123456", "patgram");
	
	if(mysqli_connect_error()) {
		echo "Failed to connect to MySQL: ".mysqli_connect_error();
	}
	
	return $conn;
}

function closeConnection($conn) {
	mysqli_close($conn);
}

function getClassIdByCode($conn, $classCode) {
	$result = mysqli_query($conn, "SELECT classId
			FROM class
			WHERE code='$classCode'");
	
	if($row = mysqli_fetch_array($result)) {
		return $row["classId"];
	}
	else {
		return null;
	}
}

?>