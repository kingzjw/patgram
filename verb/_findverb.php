<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

$prefix = $_REQUEST["p"];

$conn = createConnection();

$result = mysqli_query($conn, "SELECT value
		FROM verb
		WHERE value LIKE '$prefix%'
		ORDER BY verbId
		LIMIT 5");

$verbs = [];

while($row = mysqli_fetch_array($result)) {
	$verbs[] = [ 'value' => $row["value"] ];
}

closeConnection($conn);

echo json_encode($verbs);

?>