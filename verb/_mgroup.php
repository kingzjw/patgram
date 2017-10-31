<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

$meaningGroupId = $_REQUEST["g"];

$conn = createConnection();

$result = mysqli_query($conn, "SELECT meaning
		FROM meaning_group
		WHERE meaningGroupId=$meaningGroupId");

$group = [];

if($row = mysqli_fetch_array($result)) {
	$group["m4mg"] = $row["meaning"];
}

$group["ev"] = [];

$result = mysqli_query($conn, "SELECT A.exampleVerbId, value, IFNULL(B.classId, -1) AS highlighted
		FROM example_verb AS A
		LEFT JOIN example_verb_highlighted_in_class AS B ON A.exampleVerbId=B.exampleVerbId
		WHERE meaningGroupId=$meaningGroupId");
while($row = mysqli_fetch_array($result)) {
	$group["ev"][] = [ "evid" => $row["exampleVerbId"],
		"ev" => $row["value"],
		"hl" => ($row["highlighted"]==-1 ? false : true)];
}

$group["es"] = [];

$result = mysqli_query($conn, "SELECT value
		FROM example_sentence
		WHERE meaningGroupId=$meaningGroupId");
while($row = mysqli_fetch_array($result)) {
	$group["es"][] = $row["value"];
}

closeConnection($conn);

echo json_encode($group);

?>