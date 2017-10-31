<?php 

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

$loginFail = false;

if($_SERVER['REQUEST_METHOD']=='POST') {	
	$userName = $_POST["userName"];
	$password = md5($_POST["password"]);
	
	include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';
	
	$conn = createConnection();
	
	$result = mysqli_query($conn, "SELECT userId, firstName
			FROM user
			WHERE userName='$userName' AND password='$password'");
	
	if($row = mysqli_fetch_array($result)) {
		$userId = $row["userId"];
		$_SESSION["userId"] = $userId;
		$_SESSION["firstName"] = $row["firstName"];
		$lastLoginDate = date("Y-m-d H:i:s", time());
	
		mysqli_query($conn, "UPDATE user SET lastLoginDate='$lastLoginDate'
		WHERE userId=$userId");
	
		if(empty($_GET["r"])) {
			header("location: http://".$_SERVER["HTTP_HOST"]."/index.php");
		}
		else {
			$returnUrl = urldecode($_GET["r"]);
			header("location: http://".$_SERVER["HTTP_HOST"].$returnUrl);
		}
		
		closeConnection($conn);
		exit();
	}
	
	$loginFail = true;
	
	closeConnection($conn);
}

?>


<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.validate.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/bootstrap.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/font-awesome.php';
?>

<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/main.css">

<script>
$(document).ready(function() {
	$("#loginForm").validate();
});
</script>

</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/nav.php'; ?>
	
	<section>
		<div class="container">
			<div class="row">
			<div class="panel panel-default col-sm-offset-4 col-sm-4">
			<div class="panel-body">
				<h3>Login</h3>
				
			<?php if($loginFail) { ?>
				<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove"></span> The email address and/or password you entered is incorrect. Please try again.</div>
			<?php } ?>
			
				<form id="loginForm" class="form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
					<div class="form-group">
						<label class="control-label sr-only" for="inputUserName">Email</label>
						<input type="text" class="form-control required" id="inputUserName" placeholder="Email" name="userName"></input>
					</div>
					<div class="form-group">
						<label class="control-label sr-only" for="inputPassword">Password</label>
						<input type="password" class="form-control required" id="inputPassword" placeholder="Password" name="password"></input>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary btn-block">Login</button>
	  				</div>
				</form>
			</div>
			</div>
			</div>
		</div>
	</section>
		
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
</body>
</html>