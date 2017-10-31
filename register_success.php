<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

?>

<!DOCTYPE html>
<html>
<head>
<title>Verb</title>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.validate.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/bootstrap.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/font-awesome.php';
?>

<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/main.css">

<script>
	$(document).ready(function() {
		var seconds = 0;
		setInterval(function() {
			seconds += 1;
		    $("#remainingTime").text(6-seconds);
		    if(seconds == 6) {
			    window.location = "http://<?php echo $_SERVER["HTTP_HOST"]; ?>/login.php";
		    }
		},1000);
	});
</script>
</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/nav.php'; ?>

	<section>
		<div class="container">
			<div class="row">
				<div class="alert alert-success" role="alert">
					<span class="glyphicon glyphicon-ok"></span> Register successfully! Will be redirected to login page in <span id="remainingTime"></span> seconds, or click <a href="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/login.php">this</a>.
				</div>
			</div>
		</div>
	</section>
	
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
</body>
</html>