<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

$verb = $_REQUEST["v"];

$conn = createConnection();

$patterns = [];

if(empty($_GET["c"])) {
	$result = mysqli_query($conn, "SELECT A.meaningGroupId, A.name, A.pattern, '-1' AS highlighted
			FROM meaning_group AS A
			INNER JOIN verb AS B ON A.verbId=B.verbId
			WHERE B.value='$verb'");
}
else {
	$classCode = $_GET["c"];
	$result = mysqli_query($conn, "SELECT classId
			FROM class
			WHERE code='$classCode'");
	
	if(!($row = mysqli_fetch_array($result))) {
		echo json_encode("Error");
		exit();
	}
	
	$classId = $row["classId"];
		
	$result = mysqli_query($conn, "SELECT A.meaningGroupId, A.name, A.pattern, IFNULL(C.classId, -1) AS highlighted
			FROM meaning_group AS A
			INNER JOIN verb AS B ON A.verbId=B.verbId AND B.value='$verb'
			LEFT JOIN meaning_group_highlighted_in_class AS C
			ON A.meaningGroupId=C.meaningGroupId AND C.classId='$classId'");
}

while($row = mysqli_fetch_array($result)) {
	$patterns[] = ["vp" => $row["pattern"], "mg" => $row["name"], "mgid" => $row["meaningGroupId"],
	"hl" => ($row["highlighted"]==-1 ? false : true)];
}

closeConnection($conn);

echo json_encode($patterns);

?>