<?php

include_once 'dbconnection.php';

function generateShortUrl() {
	$chars = "abcdefghijklmnopqrstuvwxyz1234567890";
	$len = strlen($chars);
	
	$conn = createConnection();
	$url = "";

	// Try 5 times to generate a unique short Url
	for ($i=0; $i<5; $i++) {
		$num = rand(0, $len*$len*$len*$len-1);
		
		$c1 = $chars[intval($num % $len)];
		$num /= $len;
		$c2 = $chars[intval($num % $len)];
		$num /= $len;
		$c3 = $chars[intval($num % $len)];
		$num /= $len;
		$c4 = $chars[intval($num)];
		
		$result = mysqli_query($conn, "SELECT code
				FROM class
				WHERE code='$c1.$c2.$c3.$c4'");
		
		if(mysqli_num_rows($result)==0) {
			$url=$c1.$c2.$c3.$c4;
			break;
		}
	}
	
	closeConnection($conn);
	
	if($url==""){
		exit("Error");
	}
	
	return $url;
}

?>